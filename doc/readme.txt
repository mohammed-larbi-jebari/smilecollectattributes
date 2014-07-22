smile information collector for eZ Publish 4.5
version 1 beta

Written by Smile Maroc,Copyright (C)
http://www.smile.ma/

@author:arbito82@gmail.com

What is smile information collector?
--------------------------

allow you to sélect thé check box information collector
for matrix object related list and file attributes.



License
-------

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.


Requirements
------------
- eZ Publish 4.5.0+
- jquery


Tested with
-----------
4.5.0



Installation
------------

1. Copy the extension to the /extension folder, so that 
you have /extension/smilecollectattributes/* structure.

2. Enable the extension (either via administration panel or directly with 
settings files):
[ExtensionSettings]
ActiveExtensions[]=smilecollectattributes

3. Configure smilebinaryfile.ini.append.php file according to your preferences.

4.add/uncomment in config.php file the ligne :

define( 'EZP_AUTOLOAD_ALLOW_KERNEL_OVERRIDE', true );

5. run the command:
php bin/php/ezpgenerateautoloads.php -o 

to override the kernel class  kernel/classes/ezinformationcollection.php by
extension /smilecollectattributes/kernel/classes /ezinformationcollection.php

6. Apply the smilecollectionbinaryfile table to your database with the included sql file:
<eZP root>/extension/smilecollectattributes/sql/mysql/sql.sql
Using either phpmyadmin (easiest) or shell/console commands.


7. Now you can add the smilematrix or Smile File or smile Object relations datatype like any other datatype when editing classes.

8. Clear cache.

