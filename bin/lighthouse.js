(async () => {
    const lighthouse = await import('lighthouse');
    const chromeLauncher = await import('chrome-launcher');

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

    const runnerResult = await lighthouse.default(
        requestedUrl,
        lighthouseOptions,
        lighthouseConfig,
    );

    clearTimeout(killTimer);

    await chrome.kill();

    if (runnerResult) {
        process.stdout.write(JSON.stringify(runnerResult));
    } else {
        process.stdout.write(JSON.stringify({ error: 'No results returned from lighthouse' }));
    }
})();
