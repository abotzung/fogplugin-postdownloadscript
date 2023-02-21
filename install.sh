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
	
	cp -a "${basedir}/." "${docroot}${webroot}/lib/plugins/postdownloadscript"
	chown -R fogproject:www-data "${docroot}${webroot}/lib/plugins/postdownloadscript"
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
	# C'est une solution "sale" ; mais le plus simple/rapide en l'état pour créer la table.
	tempsqlfile="/tmp/addtable$RANDOM$RANDOM$RANDOM.sql"
	cat <<'EOF' >> "$tempsqlfile"
-- Adminer 4.7.9 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `postdownloadscript` (
  `pdsID` int(11) NOT NULL AUTO_INCREMENT,
  `pdsName` varchar(255) NOT NULL,
  `pdsDesc` longtext NOT NULL,
  `pdsPriority` tinyint(4) NOT NULL,
  `pdsScript` longtext NOT NULL,
  `pdsImageAssociated` int(11) NOT NULL,
  PRIMARY KEY (`pdsID`),
  UNIQUE KEY `index0` (`pdsID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;
EOF
	mysql fog < "$tempsqlfile"
	rm "$tempsqlfile"
	echo "==== Installation done ! ===="
	echo ''
	echo ' - Have a nice day !'	
	cd "$basedir" || exit
else
	echo "Goodbye"
fi
