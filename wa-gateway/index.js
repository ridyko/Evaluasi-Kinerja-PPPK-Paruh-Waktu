const { 
    default: makeWASocket, 
    useMultiFileAuthState, 
    DisconnectReason,
    fetchLatestBaileysVersion,
    makeCacheableSignalKeyStore
} = require("@whiskeysockets/baileys");
const { Boom } = require("@hapi/boom");
const pino = require("pino");
const express = require("express");
const qrcode = require("qrcode-terminal");

const app = express();
const port = 3000;
const TOKEN = "your-secret-token"; 

app.use(express.json());

let sock = null;
let qrData = null;
let isConnected = false;

async function connectToWhatsApp() {
    const { state, saveCreds } = await useMultiFileAuthState('auth_info_baileys');
    const { version, isLatest } = await fetchLatestBaileysVersion();

    sock = makeWASocket({
        version,
        printQRInTerminal: true,
        auth: state,
        logger: pino({ level: 'silent' }),
    });

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;
        
        if (qr) {
            qrData = qr;
        }

        if (connection === 'close') {
            isConnected = false;
            const shouldReconnect = (lastDisconnect.error instanceof Boom) ? 
                lastDisconnect.error.output.statusCode !== DisconnectReason.loggedOut : true;
            console.log('Connection closed, reconnecting...', shouldReconnect);
            if (shouldReconnect) {
                connectToWhatsApp();
            }
        } else if (connection === 'open') {
            console.log('WhatsApp Gateway (Baileys) is Ready!');
            isConnected = true;
            qrData = null;
        }
    });

    sock.ev.on('creds.update', saveCreds);
}

app.get('/qr', (req, res) => {
    if (qrData) {
        res.json({ status: true, qr: qrData });
    } else {
        res.json({ status: false, message: 'QR Code not available or already connected' });
    }
});

app.get('/status', (req, res) => {
    res.json({ status: true, connected: isConnected });
});

app.post('/send-message', async (req, res) => {
    const { to, message } = req.body;
    const authHeader = req.headers.authorization;

    if (!authHeader || authHeader !== `Bearer ${TOKEN}`) {
        return res.status(401).json({ status: false, message: 'Unauthorized' });
    }

    if (!isConnected) {
        return res.status(500).json({ status: false, message: 'WhatsApp not connected' });
    }

    try {
        const jid = to.includes('@s.whatsapp.net') ? to : `${to}@s.whatsapp.net`;
        await sock.sendMessage(jid, { text: message });
        res.json({ status: true, message: 'Message sent successfully' });
    } catch (error) {
        res.status(500).json({ status: false, message: error.message });
    }
});

connectToWhatsApp();

app.listen(port, () => {
    console.log(`WA Gateway (Baileys) listening at http://localhost:${port}`);
});
