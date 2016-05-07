# The Community Panel
This application has been used for communities within the game Habbo Hotel. It includes a forum, conversations, and much more. This project was kind of my "digital playground" - trying out different kinds of methods, and learning over the years. Because I have stopped playing the game, I find it a waste of my time to let the project fully die after the time I've put into it.

# Warning
In advance I do warn most of the code is very outdated. It's a mix of procedural and OOP, and the procedural parts still use MySQL, which is deprecated and even removed in PHP7. Even though the code is partly outdated and mixed up with different styles over the years, it is 100% safe.

# Updates
The chance I will update any of this is really small. If some people like to keep this project updated, you can contact me and you might me made editor of this project.

# Setting it all up
Just upload the files to your webhost. Next, import the database structure (just import the SQL file). In config.php and /_paneel/classes/db.class.php change the MySQL connection details. Once it is setted up, register yourself, add yourself as an admin in paneel_admins in the database with level 3. After that, add yourself to paneel_personeel in the database and set all the permissions to 1, which gives you access to all parts.

Finally, set up all the ranks, give yourself the highest rank through the database (just set rank_oud to 0) and everything is ready to go.

By default, the mission-code registration and Habbo imaging, are all done through the Dutch hotel. To change this, change the API calls to the Habbo API.

As for the integrated HipChat functionality, either use your own API key of your own HipChat enviroment, or throw it all away.

Also, in the rangniveau files, there's an array with people who are allowed to access it. Make sure you change it to your name.

I think that's all. If there are any issues with setting it all up, just open an issue report.

# Hostname protection
In /_paneel/classes/bans.class.php there's a function being called named canAccess. Basicly this checks your hostname against a whitelist and if it's on the whitelist, you'll be able to access. I recommend disabling this function if you don't know how to work with it or if you're working from localhost.

# License
This application and it's source code has been released under the GNU General Public License version 3. The official license document can be found in this repository. If you're Dutch, check out the unofficial Dutch translation of the GNU GPL v3 here: https://bartbeuving.files.wordpress.com/2008/07/gpl-v3-nl-101.pdf
