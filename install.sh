#!/bin/bash
#============================================================================
#
# fogplugin-postdownloadscript
#
# Auteur       : Alexandre BOTZUNG [alexandre.botzung@grandest.fr]
# Entreprise   : Région Grand-Est - Maison de région de Saverne-Haguenau
# Version      : 20230205
#============================================================================ 
# install.sh
#   Ce script déploie l'addon fogplugin-postdownloadscript
#============================================================================ 
# Oh ! Dirty !  ;
source /opt/fog/.fogsettings

basedir=$PWD

if [[ -z "${docroot}${webroot}" ]]; then
	echo "ERROR ! No FOG installation detected on this host."
	exit 1
fi

echo 'fogplugin-postdownloadscript installer'
echo ''
echo "Installer runs on server $hostname (${ipaddress})"
echo ''
echo "Welcome to the installer ! (FOG path : ${docroot}${webroot})"
echo 'This patch comes with ABSOLUTELY NO WARRANTY'
echo ''
read -n1 -p "Do you wish to install this patch (y/N) ? :" question
echo ''
if [[ "$question" == "y" || "$question" == "Y" ]]; then
	mkdir "${docroot}${webroot}/lib/plugins/postdownloadscript"
	mkdir "/images/postdownloadscripts"
	cp "/images/postdownloadscripts/fog.postdownload" "/images/postdownloadscripts/fog.postdownload_bak"
	cp "${basedir}/src/fog.postdownload.example.txt" "/images/postdownloadscripts/fog.postdownload"
	chmod +rx "/images/postdownloadscripts/fog.postdownload"
	cp "${basedir}/src/postdownloadscripts.fog.txt" "/images/postdownloadscripts/postdownloadscripts.fog"
	chmod +rx "/images/postdownloadscripts/postdownloadscripts.fog"
	
	cp "${basedir}/src/postdownloadscript.php.txt" "${docroot}${webroot}/service/postdownloadscript.php"
	chmod +rx "${docroot}${webroot}/service/postdownloadscript.php"
	
	# Active les plugins dans le système
	mysql --execute="UPDATE globalSettings SET settingValue = '1' WHERE settingKey = 'FOG_PLUGINSYS_ENABLED';" fog

	# Active le plugin PostDownload Scripts
	mysql --execute="INSERT INTO plugins (pName, pState, pInstalled, pVersion, pAnon1, pAnon2, pAnon3, pAnon4, pAnon5) VALUES ('postdownloadscript', '1', '1', '1', '', '', '', '', '');" fog

	# A cette étape, il manque la table du plugin, il faudra la créer...!

	echo "==== Installation done ! ===="
	echo ''
	echo ' - Have a nice day !'	
	cd "$basedir" || exit
else
	echo "Goodbye"
fi
