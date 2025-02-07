// const puppeteer = require('puppeteer');
// const fs = require('fs');
// const path = require('path');

// (async () => {
//     const url = "https://www.aami.com.au/policy-documents/personal/comprehensive-car-insurance.html";
//     const downloadPath = "/home/mojodojo/example-app~/downloads";

//     // Ensure the download directory exists
//     if (!fs.existsSync(downloadPath)) {
//         fs.mkdirSync(downloadPath, { recursive: true });
//         console.log(`📁 Created directory: ${downloadPath}`);
//     }

//     const browser = await puppeteer.launch({
//         headless: false, // Set false to see the browser actions
//         defaultViewport: null
//     });

//     const page = await browser.newPage();
//     await page.goto(url, { waitUntil: 'networkidle2' });

//     console.log(`✅ Page loaded: ${url}`);

//     // Click the "Download PDS" button
//     const downloadSelector = 'a[href*="PDS"]';

//     await page.waitForSelector(downloadSelector, { timeout: 10000 });
//     await page.click(downloadSelector);

//     console.log(`📌 Clicked the "Download PDS" button. Waiting for download...`);

//     await page.waitForTimeout(5000); // Wait a few seconds to see if it downloads

//     console.log("🔄 Checking if the file downloaded...");
// })();

const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({
        headless: false, // Change this to false to see the browser window
        slowMo: 100, // Slows down operations for better visibility
    });

    const page = await browser.newPage();
    await page.goto('https://www.aami.com.au/policy-documents/personal/comprehensive-car-insurance.html', {
        waitUntil: 'networkidle2'
    });

    console.log("✅ Page loaded: " + page.url());

    try {
        // Wait for the AAMI logo to appear
        await page.waitForSelector('a[href="/"]', { timeout: 5000 });

        // Check if the element exists before clicking
        const logoElement = await page.$('a[href="/"]');
        if (logoElement) {
            console.log("📌 Clicking the AAMI logo...");
            await logoElement.click();
            await page.waitForNavigation({ waitUntil: 'networkidle2' });
            console.log("✅ Navigated to: " + page.url());
        } else {
            console.error("❌ AAMI logo not found, skipping click.");
        }
    } catch (err) {
        console.error("❌ Error clicking the AAMI logo: ", err);
    }

    await browser.close();
})();


///click id 
// const puppeteer = require('puppeteer');

// (async () => {
//     const url = "https://www.aami.com.au/policy-documents/personal/comprehensive-car-insurance.html";

//     const browser = await puppeteer.launch({
//         headless: false, // Set to true if you want to run without UI
//         defaultViewport: null
//     });

//     const page = await browser.newPage();
//     await page.goto(url, { waitUntil: 'networkidle2' });

//     console.log(`✅ Page loaded: ${url}`);

//     // Button ID (Replace with actual ID)
//     const buttonSelector = '#yourButtonId';  // Example: '#exportButton'

//     // Wait for button to be available
//     await page.waitForSelector(buttonSelector, { timeout: 10000 });

//     // Click the button
//     await page.click(buttonSelector);

//     console.log(`🎯 Clicked the button with ID: ${buttonSelector}`);

//     // Wait a few seconds before closing for testing
//     await page.waitForTimeout(3000);
//     await browser.close();
// })();
