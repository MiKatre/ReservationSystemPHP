const faker = require('faker');
const puppeteer = require('puppeteer');

const person = {
  firstName: faker.name.firstName(),
  lastName: faker.name.lastName(),
  invalidEmail: 'jean@gmail.fr',
  email: faker.internet.email(),
  date: '4 octobre 2019',
};

const ticket = {
  firstName: faker.name.firstName(),
  lastName: faker.name.lastName(),
  email: faker.internet.email(),
  date: '4 octobre 2019',
};

describe('Contact Form', () => {
  test('Can fully complete the purchase process', async () => {
	let browser = await puppeteer.launch({
	  headless: false,
	  devtools: false,
	  // slowMo: 100
	});
	let page = await browser.newPage();

	page.emulate({
	  viewport: {
		width: 1366,
		height: 700
	  },
	  userAgent: ''
	});

	await page.goto('http://localhost:8888/projet4/public/form');
	await page.waitForSelector('#form');
	await page.click("input[name=firstName]");
	await page.type("input[name=firstName]", person.firstName);
	await page.click("input[name=lastName]");
	await page.type("input[name=lastName]", person.lastName);
	await page.click("input[name=email]");
	await page.type("input[name=email]", person.invalidEmail, {delay: 50});
	await page.click(".DayPickerInput > input");
	await page.type(".DayPickerInput > input", person.date);

  await page.click("input[name=firstName]");

  await page.click("button[type=submit]");

  await page.click("input[name=email]");
  await page.keyboard.down('Shift');
  for (let i = 0; i < person.invalidEmail.length; i++)
    await page.keyboard.press('ArrowLeft');
  await page.keyboard.up('Shift');
  await page.waitFor(2000);
  await page.type("input[name=email]", person.email);
  await page.waitFor(2000);
  await page.click("button[type=submit]");

  await page.waitFor(2000);

  await page.click("input[name=firstName]");
	await page.type("input[name=firstName]", ticket.firstName);
	await page.click("input[name=lastName]");
	await page.type("input[name=lastName]", ticket.lastName);

  
	await page.click("input[name=birthDate]");
  await page.select("select[name=month]", '6');
  await page.select("select[name=year]", '1995');

  await page.waitFor(2000);
	await page.click(".DayPicker-Body > .DayPicker-Week:last-child > .DayPicker-Day:first-child");
  
  
	await page.click(".country");
  await page.type(".country", 'France');
  await page.click("input[name=firstName]");
  await page.click(".ant-radio-group > .ant-radio-button-wrapper:first-child");
  await page.click("#discount");
  await page.waitFor(2000);
  await page.click("#discount");

  await page.click("div.text-center.mt-5 > button[type=submit]");
  await page.waitFor(2000);
  await page.click("div.mb-4.col-md-4.order-md-2 > div.text-center > button[type=button]");

  await page.waitFor(5000);
	await page.click("#cardNumber");
  await page.type("#cardNumber", '4242424242424242',{delay: 50});
	await page.click("#expiry-element");
  await page.type("#expiry-element", '1222', {delay: 50});
	await page.click("#cvc-element");
  await page.type("#cvc-element", '123', {delay: 50});

  await page.waitFor(2000);
  await page.click("button[type=submit]");

  await page.waitForSelector('#thank-you-message');
  await page.waitFor(2000);
  browser.close();

  }, 9000000);
});

// describe('H1 Text', () => {
//   test('homepage loads correctly', async () => {
// 	let browser = await puppeteer.launch({
// 	  headless: false
// 	});
// 	let page = await browser.newPage();

// 	page.emulate({
// 	  viewport: {
// 		width: 500,
// 		height: 2400
// 	  },
// 	  userAgent: ''
// 	});

// 	await page.goto('http://localhost:8888/projet4/public/');
// 	await page.waitForSelector('.display-4');

// 	const html = await page.$eval('.display-4', e => e.innerHTML);
// 	expect(html).toBe('Billetterie');

// 	browser.close();
//   }, 16000);
// });