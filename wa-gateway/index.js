const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const app = express();
const port = 3000;
const TOKEN = "your-secret-token"; // Sesuaikan dengan WA_GATEWAY_TOKEN di .env

app.use(express.json());

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        args: ['--no-sandbox']
    }
});

client.on('qr', (qr) => {
    console.log('SCAN QR CODE INI DENGAN WHATSAPP ANDA:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('WhatsApp Gateway is Ready!');
});

client.on('authenticated', () => {
    console.log('Authenticated successfully!');
});

app.post('/send-message', async (req, res) => {
    const { to, message } = req.body;
    const authHeader = req.headers.authorization;

    if (!authHeader || authHeader !== `Bearer ${TOKEN}`) {
        return res.status(401).json({ status: false, message: 'Unauthorized' });
    }

    if (!to || !message) {
        return res.status(400).json({ status: false, message: 'Missing parameters' });
    }

    try {
        const chatId = to.includes('@c.us') ? to : `${to}@c.us`;
        await client.sendMessage(chatId, message);
        res.json({ status: true, message: 'Message sent successfully' });
    } catch (error) {
        res.status(500).json({ status: false, message: error.message });
    }
});

client.initialize();

app.listen(port, () => {
    console.log(`WA Gateway listening at http://localhost:${port}`);
});
