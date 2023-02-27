# fogplugin-postdownloadscript
A plugin for managing post-download scripts on a FOG Server

![Script edition](https://raw.githubusercontent.com/abotzung/fogplugin-postdownloadscript/main/images/plugin.jpg)

Based on WOL plugin, this plugin lets you manage post-downloads scripts in a orderly fashion with the FOG webpage.

## How to install :
Easy way : 

 - cd ~
 - git clone https://github.com/abotzung/fogplugin-postdownloadscript.git
 - cd fogplugin-postdownloadscript
 - .\install.sh
 
 
 Manual way :

- cd (**Where you FOG Server is installed**)/lib/plugins (eg: /var/www/fog/lib/plugins)
- git clone https://github.com/abotzung/fogplugin-postdownloadscript.git
- mv fogplugin-postdownloadscript postdownloadscript
- cp ./postdownloadscript/root_images_postdownloadscripts/* /images/postdownloadscripts
- chmod +rx /images/postdownloadscripts/*
- cp ./postdownloadscript/var_www_fog_service/* ../../service
- chmod +rx ../../service/postdownloadscript.php
- Edit the file /images/postdownloadscripts/fog.postdownload.example and rename it to fog.postdownload  

...and your good to go !

Don't forget to enable the plugins into the plugin manager ! (in FOG Server)
