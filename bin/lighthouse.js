const lighthouse = require('lighthouse');
const chromeLauncher = require('chrome-launcher');

(async () => {
    const arguments = JSON.parse(process.argv.slice(2));
    const requestedUrl = arguments[0];
    const chrome = await chromeLauncher.launch(arguments[1]);
    const lighthouseOptions = {
        logLevel: 'info',
        port: chrome.port,
    };

    const lighthouseConfig = arguments[2];
    const timeoutInMs = arguments[3];

    const killTimer = setTimeout(() => chrome.kill(), timeoutInMs);

    const runnerResult = await lighthouse(
        requestedUrl,
        lighthouseOptions,
        lighthouseConfig,
    );

    clearTimeout(killTimer)

    await chrome.kill();

    process.stdout.write(JSON.stringify(runnerResult));
})();
