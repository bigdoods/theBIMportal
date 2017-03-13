# The BIMportal.
_BIM Applications collection and dashboard_

## Description

The BIMportal is a collaborative working tool for multiple stakeholder engagement on a BIM project.

It is designed as a modular collection of BIM Applications built on a Linux/Apache/mySQL/PHP technology stack using the [codeigniter 2.2](http://www.codeigniter.com/userguide2/toc.html) MVC framework.

## Preview
<a href="http://www.youtube.com/watch?feature=player_embedded&v=dNUFRohKjM8
" target="_blank"><img src="http://img.youtube.com/vi/dNUFRohKjM8/0.jpg"
alt="theBIMportal feature video" width="640" height="480" border="10" /></a>

## Live demo available here - www.thebimportal.com

Login details for Admin dashboard
Username: admin@yourcompany.com
Password: 01234567

(Click "PORTAL" in top right screen to enter User view.)

## Getting started (Development)

Install and setup requirements to run codeigniter or recommended - use the development environment [here](https://github.com/r8/vagrant-lamp).

Replace the default files in local.dev with the contents of this repo.

    $ rm PATH/TO/public/local.dev/* && git clone <this repo git address> /path/to/public/local.dev/

Ssh into the box

    $ vagrant ssh

Connect to mysql. Password=vagrant

    $ mysql -u root -p

Create a database instance and switch to it

    $ CREATE DATABASE empty_portal;
    $ USE empty_portal;

Reload sql dump file. Empty found in db_dumps folder.

    $ SOURCE <path to .sql dump file>

Exit and make sure that your hosts file allows access for vagrant as described [here](https://github.com/r8/vagrant-lamp#notes)

    $ exit

Fire up the app! Open a browser at http://local.dev and check it out!

## Getting started (Production)

The list of commands (with comments) that are used to deploy theBIMportal to an instance on google cloud platform can be found in (this file)[https://github.com/jenca-cloud/theBIMportal/blob/master/GCP-deployment.md] 

### List of applications in the application directory currently include the following:

* Process map
* Account management
* Team feedback
* Filemanager
* Gantt chart
* Help app
* Issue viewer app
* Messaging app
* Model viewer
* Print3d request
* Project app
* Project team app
* QTO app
* Render app
* Request file
* Site Photographs
* Test app
* Ticket app (named 'activities' in app)
* Timeline app

Applications can be split into the following categories according to their function on a project using BIM.
* Core Apps
* Project Data Apps
* BIM Apps
* BIM Applied technology Apps

Third party dependencies:
* 3D model viewer - catenda BIMsync. Config file can be found in local.dev<config<bimsync.php
* Document viewer - CADsoft tools
* Autodesk Navisworks - Gantt chart and clash export. Version 2014 tested.
* LucidChart - Used to get process maps as iFrame

### The BIMportal userguide

*There is a more comprehensive user guide inside the live platform. Below is admin guide* @todo

* New user registration

Once you have setup the BIMportal you will have admin status on all projects. It is your responsibility to manage users admission on projects. Advise new users to visit the sign-in page and complete registration details. Once complete, you will see their name appear under the users tab in the admin portal. Use the dropdown menu under assign projects to choose which onces they have access to.

* New project setup

To initialise a new project, in the admin tab, select create and complete project details.
There is also the option to edit details for existing projects here also. Error: PHP error in offline mode. - check is this the same online? Think that this is tryign to reference google maps.
You can also assign a bimsync model project from the edit also.

* Application control

Perform administrative tasks on applications that are made available to users of the BIMportal through the 'Apps' tab. Set Application name, description, position of the app in the menu bar, categorize application relevance, extend platform capability by adding new PHP module/class and upload Icon to represent app in application selection bar.


### More information and some system design considerations from authors

*This belongs in a blog-post but can live here for now*

The BIMportal is an open source application framework consisting of multiple applications to increase team productivity for teams involved with BIM procurement that want minimum overhead to maintain BIM projects. The initial insentive to developing the tool was to provide a central service to support the platform where a core team would maintain and upgrade all of the BIM data with the help of a the peripheral user base. This means that there would always be a direct line of communication between all users and the operatives where data would be served on demand to the user. This significantly reduces the need for end users to embark on the learning curve required to operate software and give them a more gradual and self learning approach as they observe the functioning system from an external perspective.

Key parameters incorporated into the design of the system included the following:

* Modular and extensible framework that could accommodate new application development and deployment.
* Accessible interface for non BIM software users.
* Prototype through an established and robust technology stack (relative to 2014)
* Administrators side to platform to perform project administration, user management, application configuration and to manage content for users.
* A set of tools for non technical users to edit, upload, request and access all data for all projects.


### Todo

- Update model viewer to bimviews
- Use BIMserver as model server
- Known Issue : the newsfeed is currently shared amongst projects. It must be only shared on the main feed and filtered amongst


### License

[MIT License](http://opensource.org/licenses/MIT)


### Project contributors

- [Jenca](http://jenca.io)
- [BIMscript](http://bimscript.com)
- [AquaLeaf](http://www.aqualeafitsol.com/)
- [Daniel Craig](https://www.linkedin.com/in/daniel-craig-69a045b1)
- [Dayonemedia](https://dayonemedia.co.uk/)


### Contributing

We welcome any contributions and are here to help for any developers looking to get started.
