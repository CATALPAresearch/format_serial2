
Quelle: https://github.com/matiassingers/awesome-readme

<h1 align="center">
  <br>
  <a href="http://www.amitmerchant.com/electron-markdownify"><img src="https://raw.githubusercontent.com/amitmerchant1990/electron-markdownify/master/app/img/markdownify.png" alt="Markdownify" width="200"></a>
  <br>
  SERIAL2
  <br>
</h1>

<h4 align="center">SERIAL2 is a *Learning Analytics Dashboard* for supporting Self-regulated Learning in <a href="http://electron.atom.io" target="_blank">Moodle</a> courses.</h4>

<p align="center">
  <a href="https://badge.fury.io/js/electron-markdownify">
    <img src="https://badge.fury.io/js/electron-markdownify.svg"
         alt="Gitter">
  </a>
  <a href="https://gitter.im/amitmerchant1990/electron-markdownify"><img src="https://badges.gitter.im/amitmerchant1990/electron-markdownify.svg"></a>
  <a href="https://saythanks.io/to/bullredeyes@gmail.com">
      <img src="https://img.shields.io/badge/SayThanks.io-%E2%98%BC-1EAEDB.svg">
  </a>
  <a href="https://www.paypal.me/AmitMerchant">
    <img src="https://img.shields.io/badge/$-donate-ff69b4.svg?maxAge=2592000&amp;style=flat">
  </a>
</p>

<p align="center">
  <a href="#key-features">Key Features</a> •
  <a href="#how-to-use">How To Use</a> •
  <a href="#download">Download</a> •
  <a href="#credits">Credits</a> •
  <a href="#related">Related</a> •
  <a href="#license">License</a>
</p>

![screenshot](https://raw.githubusercontent.com/amitmerchant1990/electron-markdownify/master/app/img/markdownify.gif)

## Key Features

* visualize moodle activities on the timeline
  - ...
* enable the user to define milestones by defining a learning objective, necessary ressources, and apropriate learning strategies

**Roadmap**
* show milestones at the ressource pages (e.g. in the forum)
* add additional dashboard elements


## How To Use

To clone and run this application, you'll need [Git](https://git-scm.com) and [Node.js](https://nodejs.org/en/download/) (which comes with [npm](http://npmjs.com)) installed on your computer. From your command line:

```bash
# Clone this repository
1. `git clone`  the repository to /your-moodle/course/format/

# Rename the folder to 'serial2'

# Go into the repository
$ cd serial2

# Install dependencies
$ cd vue
$ npm install

# Build the plugin by transpiling the vue code into javascript
$ npm run build

# Open the page https://<moodle>/admin/index.php?cache=1 and follow the install instructions for the plugin.

# Open a Moodle course of you choice and go to the *course settings* (watch out for the littel cog-icon). Set the 'course format' to 'SERIAL 2'.

```

**Notes**
* In case of an error like "ERR_OSSL_EVP_UNSUPPORTED" run `export NODE_OPTIONS=--openssl-legacy-provider`
* Compile errors like broken strings may be caused by wrong version of node.js. Install node v18 (check version `node --version`) and rerun `$ npm run build`.


## Download

You can [download](https://github.com/amitmerchant1990/electron-markdownify/releases/tag/lates) the latest installable version of SERIAL2 for Moodle 3.11 and Moodle 4.x.

## Getting into Development

- api.php: In this file you'll find the SQL queries. All API get or post requests implement  webservices. The naming conventions are very strict. Take care to to follow the nameing schema for the functions. 
- db/services.php: Here your are defining the webservice by referencing the involved files (api.php and its containing classes and functions)
- version.php: Every time you are changing the webservice description in service.php you need to increment the version by 1 and update the plugin in moodle (See the *notifications* page in the administration panel)
- amd/scr: This is the folder where all custome javascript is located.
- lib: This the folder where all third party javascript is stored.
- /amd/serial2.js: This is the only script that is called by the HTML-DOM of the plugin. By using require.js all other files and dependencies are loaded on demand in this file and passed to the components (like the Timeline or the Assessment) underneath.
- amd/src/Assessment.js: This the major file where your assment dashboard has to be written. All necessray dependencies (d3, dc, vue) should be available there. The data from the server should be provided in the function call inside /amd/serial2.js, just below the Timeline call.

A good sheet sheet: http://tech.solin.eu/doku.php?id=moodle:course_construction


## Emailware

SERIAL2 is an [emailware](https://en.wiktionary.org/wiki/emailware). Meaning, if you liked using this plugin or it has helped you in any way, I'd like you send me an email at <niels.seidel@fernuni-hagen.de> about anything you'd want to say about this software. I'd really appreciate it!

## Credits

This software uses the following open source packages:

- [Node.js](https://nodejs.org/)
- [Vue.js](https://vuejs.org/)
- vuex
- vue-router

## Related

[SERIAL 1](https://github.com/catalparesearch/serial/serial1) - Precessor
[SERIAL 3](https://github.com/catalparesearch/serial/serial1) - Successor

## Support

<a href="https://www.buymeacoffee.com/5Zn8Xh3l9" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/purple_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>


## You may also like...

* x
* y

## License

GPLv3


## Contributors
* Niels Seidel [@nise81](https://twitter.com/nise81)
* Heike Karolyi
* Slavisa Radovice

---



