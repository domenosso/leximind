export default async function handler(req, res) {
    // Разрешаем CORS
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        return res.status(200).end();
    }

    if (req.method !== 'POST') {
        return res.status(405).json({ error: 'Method not allowed' });
    }

    const { prompt } = req.body;

    const sendData = {
        "model": "gpt-5.2-chat",
        "request": {
            "messages": [
                {
                    "role": "user",
                    "content": prompt
                }
            ]
        }
    };

    try {
        const response = await fetch('http://api.onlysq.ru/ai/v2', {
            method: 'POST',
            headers: {
                "Authorization": "Bearer openai",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(sendData)
        });

        const data = await response.json();
        return res.status(200).json(data);
    } catch (error) {
        return res.status(500).json({ error: 'Ошибка сервера при запросе к ИИ' });
    }
}
