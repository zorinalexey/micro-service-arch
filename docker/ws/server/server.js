const WebSocket = require('ws');
const { format } = require('date-fns');

const wss = new WebSocket.Server({ port: 3000 });
const channels = {};

wss.on('connection', (ws) => {
    console.log('Клиент подключен');

    ws.on('message', (message) => {
        let channel = null;
        const data = JSON.parse(message);

        if(data.channel !== undefined){
            channel = data.channel;
        }

        let subscribe = 'subscribe';
        data.date = format(new Date(), 'yyyy-MM-dd HH:mm:ss');

        if(data[subscribe] !== undefined){
            for (let newChannel of data[subscribe]) {
                if (!channels[newChannel]) {
                    channels[newChannel] = [];
                }

                if (!channels[newChannel].includes(ws)) {
                    channels[newChannel].push(ws);
                }
		    }
        }

	    if(channel){
        	if (!channels[channel]) {
       	        channels[channel] = [];
        	}

            if (!channels[channel].includes(ws)) {
           	    channels[channel].push(ws);
       	    }

        	channels[channel].forEach(client => {
               	if (client.readyState === WebSocket.OPEN && data) {
               	    client.send(JSON.stringify(data));
               	    console.log('Сообщеение отправлено в канал: ' + channel, data);
               	}
       	    });
	    }
    });

    ws.on('close', () => {
        for (const channel in channels) {
            channels[channel] = channels[channel].filter(client => client !== ws);
        }
    });
});
