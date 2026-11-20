// Records the stage page to an MP4 frame by frame. The page runs on a manual clock (see #record in stage.blade.php),
// so the result is smooth regardless of how fast the machine renders.
//
//   node record.cjs <stage.html> <out.mp4> [fps]
//
// Needs puppeteer-core and ffmpeg-static (resolve them via NODE_PATH) and an installed Edge/Chrome
// (set BROWSER=<path> to override the default Edge location).
const fs = require('fs');
const { spawn } = require('child_process');
const puppeteer = require('puppeteer-core');
const ffmpegPath = require('ffmpeg-static');

const [html, out, fpsArg] = process.argv.slice(2);
const FPS = parseInt(fpsArg || '25', 10);
const BROWSER = process.env.BROWSER || 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';

(async () => {
  const browser = await puppeteer.launch({
    executablePath: BROWSER,
    headless: 'new',
    args: ['--disable-gpu', '--hide-scrollbars', '--autoplay-policy=no-user-gesture-required'],
  });
  const page = await browser.newPage();
  await page.setViewport({ width: 540, height: 960, deviceScaleFactor: 2 });
  const client = await page.createCDPSession();

  await page.goto('file:///' + html.replace(/\\/g, '/') + '#record', { waitUntil: 'load' });
  await page.evaluate(() => document.fonts.ready);
  await page.evaluate(() => window.__start());
  const total = await page.evaluate(() => window.__done);
  const frames = Math.ceil((total / 1000) * FPS);
  console.log(`recording ${frames} frames @ ${FPS}fps (${(total / 1000).toFixed(1)}s)`);

  const ff = spawn(ffmpegPath, [
    '-y', '-f', 'image2pipe', '-framerate', String(FPS), '-i', '-',
    '-c:v', 'libx264', '-preset', 'medium', '-crf', '20', '-pix_fmt', 'yuv420p',
    '-movflags', '+faststart', out,
  ], { stdio: ['pipe', 'inherit', 'inherit'] });

  const step = () => page.evaluate((dt) => window.__advance(dt), 1000 / FPS);

  for (let i = 0; i < frames; i++) {
    await step();
    const buf = await page.screenshot({ type: 'jpeg', quality: 92 });
    if (!ff.stdin.write(buf)) await new Promise((r) => ff.stdin.once('drain', r));
    if (i % 100 === 0) console.log('frame', i);
  }
  ff.stdin.end();
  await new Promise((r) => ff.on('close', r));
  await browser.close();
  console.log('done', out);
})().catch((e) => { console.error(e); process.exit(1); });
