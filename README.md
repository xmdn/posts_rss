# Project Name

Rss Api with Admin UI

## Description

Parcer backend with worker queues, on Laravel v8, with JWT Auth.

Vue.js v3 (composition api) frontend as Admin UI.

MySql as DBMS.

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone this repository to your local machine.
   ```bash
   git clone https://github.com/xmdn/posts_rss

2. Open terminal.

3. Move to directory cd /<your-project-folder>/rss_api.

4. Setup your localhost for frontend to call backend api in /vue-app/.env.
(in my case i used remote linux machine so i called it thru local ip of machine, so you should change API_BASE_URL in env)

Also you can change ports. (vite.config, docker-compose.yml)

If you want some custom apache config you can setup it in apache-config.conf before building

## IMPORTANT ##
if you experience problems with building and using Windows, it might be because you need to setup git and IDE so you use '\n' as line endings, and not Windows '\r\n'
## IMPORTANT ##

4. Build docker with: docker-compose up --build (if you dont want to see logs, add '-d' option after --build).


## ENV STRUCTURE ##
--lara_api
    -.env
    -vendor
    -artisan

--vue-app
    -.env
    -Dockerfile of vue-app
    -node-modules
    -package.json

-Dockerfile of lara_api
-docker-compose.yml