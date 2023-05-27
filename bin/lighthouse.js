import chromeLauncher from 'chrome-launcher';
import lighthouse from 'lighthouse';

(async () => {
    const args = JSON.parse(process.argv.slice(2));
    const requestedUrl = args[0];
    const chrome = await chromeLauncher.launch(args[1]);
    const lighthouseOptions = {
        logLevel: 'info',
        port: chrome.port,
    };

    const lighthouseConfig = args[2];
    const timeoutInMs = args[3];

    const killTimer = setTimeout(() => chrome.kill(), timeoutInMs);

    const runnerResult = await lighthouse(
        requestedUrl,
        lighthouseOptions,
        lighthouseConfig,
    );

    clearTimeout(killTimer);

    await chrome.kill();

    process.stdout.write(JSON.stringify(runnerResult));
})();