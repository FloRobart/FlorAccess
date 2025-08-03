import app from './app';
import config from './config/config';
import http from 'http';
import * as logger from './utils/logger';



/*==================*/
/* Écoute du server */
/*==================*/
const server = http.createServer(app);

try {
    server.listen(config.app_port, config.host_name, () => {
        logger.success("Server running at URL :", config.base_url, "!");
        logger.success("Server documentation running at URL :", config.base_url + "/api-docs", "!");
        logger.success("Server running at HOST :", config.host_name, "!");
    }).on("error", (error) => {
        logger.error("FAILED STARTING SERVER\n");
        throw new Error(error.message);
    });
} catch (error) {
    logger.error("Error in main index.ts :", error);
    process.exit(1);
}