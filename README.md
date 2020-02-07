# DS IMAGE STORAGE

---

This is a demo project and is not meant to be used as real gallery as emphasis is
placed on demonstration of functionality.

### Install/Setup

Set 'public/' as DocumentRoot in apache conf and set dir/subdirs to read-write.
To create base sql skeleton, copy and run query sql content in database.db to phpMyAdmin.  


### Files

* public/assets - w3.css and shared js
* public/image_data - image and thumbnail data
* public/index.php - entry point
* template/ - html/js/css view modules
* App.php - startup code
* Controller.php - ajax and url parameter parsing
* View.php - template dir page compiling
* Image.php - image resize and storage
* User.php - user management
* Model.php - sql methods
* DbConnection.php - pdo db connection
* Core.php - log and config methods
* Config.php - project configuration
* database.db - sql skeleton


### Project Structure

Classes are separated in files logically and extend one another, keeping everything
under one roof.


### Template

View elements (html/js) are in 'template' directory.
Php files in 'template' directory decide what html element will be
displayed from php and pass sql/session data as json to html page.


### Navigation 

Page navigation links are GET requests (for example in:'/template/layout/navbar.html').
Javascript code formed like this:  window.location.href = getRequestUrl() + '?dst=account'.
'getRequestUrl()' js function returns clean url. Url parameters are captured/parsed in 
'Controller.php' as parameters with key 'dst' (short for destination). After 
that, view elements of page are compiled, cached (ob_start) and displayed. 


### Image List

Sql query is made to check if there is any images. List is created with single query:

        "SELECT `id` AS `imageid`, `filename`, `type`, `size`, `user_id` AS `userid`, 
        (SELECT `name` FROM `users` WHERE `id` = `user_id`) AS `username`,
        (SELECT `email` FROM `users` WHERE `id` = `user_id`) AS `email`,
        CONCAT(`user_id`, '_', `id`, '.', `type`) AS `newname`
        FROM `images`"; 
        
Fetched data is converted into json and passed into js as object on page compile.
After that 'createListItem()' js function creates 'li' elements with json data 
and thumbnail images. 


### Images

Images are stored in 'public/image_data' directory. Upload size is limited to
2MB. Javascript is used to block upload of larger files by checking size property of
file input html node. 'uploadImage' method collects information about image, stores
info into sql database, creates thumbnail and returns new data back as json 
as response to XMLHttpRequest. Stored images are string formatted in userid_imageid.extension or
userid_imageid_t.extension for thumbnail images for use with glob function.

---

### Copyright And License

Damir Šijaković (c) 2019, MIT Licence 
