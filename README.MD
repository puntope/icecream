# Icecream 


## How to install the project

- Download the repo

`git clone https://github.com/puntope/icecream.git`

- Create the .env file

`cd icecream`

`cp .env.example .env`


- Run docker

`cd icecream/docker && docker-compose up --build`

- Import DB
cd 
`cd icecream/db && docker exec -i icecream-database mysql -uroot -psomewordpress wordpress < dump.sql
`

- Enjoy

`http://localhost`
`http://localhost/wp-admin` User:admin Pass: admin



## How it works

- For one side is the Wordpress admin

There you can see the icecream postType (with the icecreams you need in the pages). I basically render the image and content for the first 3.

Then, insde the Pages, you can set the feature image, the form, hubspot portal and ID, heading, cta...

In contact you can set the different forms you want, just set the html there and after, you can use that forms in the different pages of the site.


- For other side is frontend part (mobile and desktop) and the boilerplate 


`/db` - Contains basically a dump.sql from my database

`docker` - The docker comfiguration, such apache conf, composer.json (there are the wp plugins and installers)

`website` - contains enviroment config and the code

`website/.env` - Project settings

`webiste/config` - enviroment config (depends on your WP_ENV settings)

`webiste/public` - THE CODE . Bacisally the Icecream theme

The theme contains some important stuff

`assets` - SCSS, JS, Images, Fonts...and other assets

`includes` - PHP Files, functions & other usefull stuff

`templates` - Different twig templates

`functions.php` - A lot of nice stuff happen here

`page.php` - A sample basic Page PHP file.


The rest, like plugins or WP core files are handled by Composer with docker (see Dockerfile).




## How to play with the project

- Download the repo

`git clone https://github.com/puntope/icecream.git`

- Create the .env file

`cd icecream`

`cp .env.example .env`

- Set WP_ENV to local in .env file
`WP_ENV=local`

- Run docker

`cd icecream/docker && docker-compose up --build`


- Import DB

`cd icecream/db && docker exec -i icecream-database mysql -uroot -psomewordpress wordpress < dump.sql`


- Go to the them dir and run some npm installations

`cd icecream/website/public/themes/icecream`
`npm install` or `npm i` if you are a cool guy.

- Start webpack

`npm run dev`

- Enjoy

`http://localhost`
`http://localhost/wp-admin` User:admin Pass: admin










