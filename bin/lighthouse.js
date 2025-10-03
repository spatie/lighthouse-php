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
    const maxWaitForLoad = arguments[4];

    if (maxWaitForLoad !== null && maxWaitForLoad !== undefined) {
        lighthouseOptions.maxWaitForLoad = maxWaitForLoad;
    }

    const killTimer = setTimeout(() => chrome.kill(), timeoutInMs);

    const runnerResult = await lighthouse.default(
        requestedUrl,
        lighthouseOptions,
        lighthouseConfig,
    );

    clearTimeout(killTimer);

    await chrome.kill();

    const output = {
        report: runnerResult.report,
        runtimeError: runnerResult.lhr?.runtimeError || null
    };

    process.stdout.write(JSON.stringify(output));
})();
