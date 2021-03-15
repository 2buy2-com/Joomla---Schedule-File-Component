# Schedule File Component - Joomla

A mechanism used within a Joomla Admin section to manage all schedule files.

# Getting Started

These instructions will allow you to install the *com_schedulefiles* to your Joomla website

## Pre-requisites

* Your [Joomla](https://www.joomla.org/) CMS must be a minimum of version 3.9

## Deployment

* [Download this repo](https://github.com/2buy2/component-schedulerFile/archive/master.zip)
* Copy and paste the *com_schedulefiles* folder into *[SITE ROOT]/administrator/components*
* Within your Joomla instance, navigate to */administrator/index.php?option=com_installer&view=discover*, check the checkbox and click *Install*
* Using [PLESK](https://web2.2buy2.com:8443/) navigate to *Scheduled Tasks* -> [*Add Task*](https://web2.2buy2.com:8443/smb/scheduler/add-task)
  * Subscription = Website this applies to
  * Task Type: Fetch a URL
  * URL: *MAIN URL/index.php?option=com_schedulefiles&view=schedulefiles&extension=com_schedulefiles&format=json&task=run_file*
  * Run: Hourly
  * Description: WEBSITE NAME Scheduled Tasks component
  * Notify: Errors only
  * Then click *Save*

# Built With

This product has been built with [Joomla](https://www.joomla.org/) API
# Authors

* **David Hendy** - *Initial work* - [2buy2](https://www.2buy2.com)