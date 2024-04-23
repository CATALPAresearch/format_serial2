
<br>
<div align="center">
<img src="pix/promotion/screenshot1.png"  style="border:1px solid #ccc; padding:6px;"  width="600" />
</div>

<br>
<h1 align="center">Serial2</h1>

## *Serial2* is a Moodle course format plugin supporting self-regulated learning in terms of planning milestones, monitoring learning activities, and reflecting past learning activities.


*Serial2* (format_serial2) is a *Learning Analytics Dashboard* for Moodle courses. The dashboard presents moodle activities on a timeline and enables the user to define milestones. Each milestone is associated with learning objectes, a deadline, certain course ressources and learning strategies to be applied.


<!-- development-related badges -->
[![Maintenance](https://img.shields.io/badge/Maintained%3F-no-red.svg)](https://github.com/CATALPAresearch/format_serial2/commit-activity)
[![github latest commit](https://badgen.net/github/last-commit/CATALPAresearch/format_serial2)](https://github.com/CATALPAresearch/format_serial2/commit/)
[![github contributors](https://badgen.net/github/contributors/CATALPAresearch/format_serial2)](https://github.com/CATALPAresearch/format_serial2/contributors/)
[![github issues](https://img.shields.io/github/issues/CATALPAresearch/format_serial2.svg)](https://github.com/CATALPAresearch/format_serial2/issues/)
[![GPLv3 license](https://img.shields.io/badge/License-GPLv3-green.svg)](http://perso.crans.org/besson/LICENSE.html)

![https://img.shields.io/badge/any_text-you_like-blue](https://img.shields.io/badge/Tested_Moodle_versions-3.5_to_3.11-green)
![](https://img.shields.io/badge/PHP-7.4_to_8.0.29-green)
![](https://img.shields.io/badge/vue.js-2-green)

<!-- Maturity-related badges 
see: https://github.com/mkenney/software-guides/blob/master/STABILITY-BADGES.md
-->
[![stability-deprecated](https://img.shields.io/badge/stability-deprecated-922b21.svg)](https://github.com/mkenney/software-guides/blob/master/STABILITY-BADGES.md#deprecated)
[![stability-experimental](https://img.shields.io/badge/stability-experimental-orange.svg)](https://github.com/mkenney/software-guides/blob/master/STABILITY-BADGES.md#experimental)
![](https://img.shields.io/badge/years_in_productive_use-1-darkgreen)
![](https://img.shields.io/badge/used_in_unique_courses-1-darkgreen)



<!-- AI-related and LA-related badges -->
<!-- 
https://nutrition-facts.ai/

Privacy Ladder Level
Feature is Optional
Model type
Base model
Base Model Trained with Customer Data
Customer Data is Shared with Model Vendor
Training Data Anonymized
Data Deletion
Human in the Loop
Data Retention
Compliance
-->
![](https://img.shields.io/badge/collects_clickstream_data-yes-blue)
![](https://img.shields.io/badge/collects_scroll_data-no-blue)
![](https://img.shields.io/badge/collects_mouse_data-yes-blue)
![](https://img.shields.io/badge/collects_audio_data-no-blue)
![](https://img.shields.io/badge/collects_video_data-no-blue)
![](https://img.shields.io/badge/data_shared_with_vendor-no-blue)


<br><br>
<p align="center" >
   <img src="pix/promotion/screenshot2.png" style="border:1px solid #ccc; padding:6px;" width="500" /><br><br>
   </p>

<p align="center">
  <a href="#key-features">Key Features</a> •
  <a href="#how-to-use">How To Use</a> •
  <a href="#download">Download</a> •
  <a href="#credits">Credits</a> •
  <a href="#related">Related</a> •
  <a href="#citation">Citation</a> •
  <a href="#license">License</a>
</p>


## Key Features

**Supporting self-regulated learning**
- With regard to Bary Zimmerman's SRL model this tool mainly supports the performance phase through monitoring instruments
- Learning progress and success is visualized as barcharts per course section.
- The color of the bars depends on pre-defined threshold realted to three learning goals the user can chose: (1) mastery, (2) just passing the exam, (3) getting an overview of the course. During the course learners can switch their goal whenever they want. 
- In addition to the monitoring students are asked to do a reflection task as soon as they have finished a course section. The refelction tasks consists of open ended questions about how well the past learning phase could be conducted including hurdley and things to improve in the future.

**Learning Analytics included**
* The plugin collectd clickstream data including mouse over events. 


## Roadmap and Limitations
This plugin is not maintained anymore. Instead its successor [SERIAL3](https://github.com/CATALPAresearch/format_serial3) is under active maintance.

## How To Use

To clone and run this application, you'll need [Git](https://git-scm.com) and [Node.js](https://nodejs.org/en/download/) (which comes with [npm](http://npmjs.com)) installed on your computer. From your command line:

```bash
# Clone this repository
1. Clone  the repository to /your-moodle/course/format/
$ git clone git@github.com:catalparesearch/format_serial2.git

# Rename the folder to 'serial2'
$ mv format_serial2 serial2

# Go into the repository
$ cd serial2

# Install dependencies
$ cd vue
$ npm -i

# Build code using webpack
$ npm run build

# Open the page https://<moodle>/admin/index.php?cache=1 and follow the install instructions for the plugin or
$ php admin/cli/uninstall_plugins.php --plugins=format_serial2 --run

# Now, login to your Moodle as an administrator. The install/update GUI should open automatically. Just follow the steps the GUI presents to you and you should have installed the *Serial2* plugin successfully afterwards. As an alternative to using the GUI for installation, you can also run the update script from within the folder of your Moodle installation:
$ php admin/cli/upgrade.php

# Open a course of you choice and go to the *course settings* (watch out for the littel cog-icon). In the section 'course format' select 'serial2'. After saving your course will be presented with Serial2

```

## Download

You can [download](https://github.com/catalparesearch/format_serial2/releases/tag/latest) the latest installable version of *Serial2* for Moodle 3.11.

## Getting into Development

* api.php: In this file you'll find the SQL queries. All API get or post requests implement  webservices. The naming conventions are very strict. Take care to to follow the nameing schema for the functions. 
* db/services.php: Here your are defining the webservice by referencing the involved files (api.php and its containing classes and functions)
* version.php: Every time you are changing the webservice description in service.php you need to increment the version by 1 and update the plugin in moodle (See the *notifications* page in the administration panel)
* amd/scr: This is the folder where all custome javascript is located.
* lib: This the folder where all third party javascript is stored.
* /amd/serial2.js: This is the only script that is called by the HTML-DOM of the plugin. By using require.js all other files and dependencies are loaded on demand in this file and passed to the components (like the Timeline or the Assessment) underneath.
* amd/src/Assessment.js: This the major file where your assment dashboard has to be written. All necessray dependencies (d3, dc, vue) should be available there. The data from the server should be provided in the function call inside /amd/serial2.js, just below the Timeline call.


## Emailware

*Serial2* is an [emailware](https://en.wiktionary.org/wiki/emailware). Meaning, if you liked using this plugin or it has helped you in any way, I'd like you send me an email at <niels.seidel@fernuni-hagen.de> about anything you'd want to say about this software. I'd really appreciate it!

## Credits

This software uses the following open source packages:
[vue.js](https://vuejs.org/), 

## Related

tba.

## Citation

> Seidel, N., Burchart, M., & Karolij, H. (2024). SERIAL1 - Moodle activity plugin supporting learner in reading longer texts. https://doi.org/10.17605/OSF.IO/VFSMD

```
@misc{Seidel2024-MoodleSerial2,
author = {Seidel, Niels and Burchart, Mark and Karolij, Heike},
doi = {10.17605/OSF.IO/VFSMD},
keywords = {P-APLE-II,software},
title = {{SERIAL1 - Moodle activity plugin supporting learner in reading longer texts}},
url = {https://github.com/CATALPAresearch/format{\_}serial2},
year = {2024}
}
```

**Research articles and datasets about Serial2:**

* Radović, S., Seidel, N., Menze, D., & Kasakowskij, R. (2024). Investigating the effects of different levels of students’ regulation support on learning process and outcome: In search of the optimal level of support for self-regulated learning. Computers & Education, 215, 105041. https://doi.org/https://doi.org/10.1016/j.compedu.2024.105041



## You may also like ...

* [SERIAL 1](https://github.com/catalparesearch/serial/serial2) - Precessor
* [SERIAL 3](https://github.com/catalparesearch/serial/serial2) - Successor


## License

[GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html)


## Contributors
* Niels Seidel [@nise81](https://twitter.com/nise81)
* Slavisa Radovic

---
<a href="https://www.fernuni-hagen.de/english/research/clusters/catalpa/"><img src="pix/promotion/catalpa.jpg" width="300" /></a>
<a href="https://www.fernuni-hagen.de/"><img src="pix/promotion/fernuni.jpg" width="250" /></a>








