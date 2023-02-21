<?php
/**
 * Tasking for PostDownloadScript (sends scripts to FOG Stub) (backend)
 *
 * PHP version 5
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
/**
 * Tasking for PostDownloadScript (sends scripts to FOG Stub) (backend)
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
class PostDownloadScriptTasking extends FOGBase
{
    /**
     * The actions supported PostDownloadScriptTasking.
     *
     * @var array
     */
    protected $actions = array(
        'ping',
        'scriptlookupbyimgid' /*scriptlookupbyuuid ? */
    );
    /**
     * Initializes the PostDownloadScript tasking class.
     *
     * @return some thing
     */
    public function __construct()
    {
        parent::__construct();
        switch (strtolower($_REQUEST['action'])) {
		case 'ping':
			// Instruction bidon pour tester si le plugin est bien installé côté FOG Server
			echo 'pong ';
			break;
        case 'scriptlookupbyimgid':
			// Prends comme paramètre : 
			//   -> imgid     ; (obligatoire) ; l'identifiant de l'image 
			//   -> pdsnumber ; (optionel)    ; Le numéro du script (par ordre de prioritée croissante 0->127)
		
            if (!isset($_REQUEST['imgid'])
                || empty($_REQUEST['imgid'])
            ) {
                break;
            }
            try {
				$ImgID = $_REQUEST['imgid'];
				$NbrScript = 0;
				$NbrScriptTEMP = 0;
				
				// On compte déjà les scripts par avance, ça évite un recalcul plus loin...
				if( FOGCore::getClass('PostdownloadscriptManager')->count()>0 ) {
					for($i = 0;$i<128;$i++) {
						foreach ((array)self::getClass('PostdownloadscriptManager')
							->find(array('priority' => $i)) as &$PDSScript) {
								if ($PDSScript->get('quelimage') == $ImgID) {
									$NbrScript++; // Incrémente de 1 à chaque fois qu'un script est dispo.
								}
							unset($PDSScript);
						}
					}
				} else {
					echo '#!noscr'."\n"; // Pas de scripts dispo
					exit;
				}					
				
				if (!isset($_REQUEST['pdsnumber']) || empty($_REQUEST['pdsnumber'])) {
					// si pdsnumber = 0 alors on renvoie le nombres de script à balancer
					// SINON on renvoie le script associée au numéro. (rien si le script n'existe pas)
					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Mode : Comptage de scrips ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					echo '#!ok='."\n"; // Ok, commande reçu
					echo $NbrScript;   // Renvoie le nombre de scripts existants pour cet image
					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ FIN MODE COMPTAGE DE SCRIPTS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				} else {
					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Mode : Balance le script ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					$numeroduScript = $_REQUEST['pdsnumber'];
					if ($numeroduScript<1 || $numeroduScript>$NbrScript) {
						echo '#!err'."\n"; // Hors limites ... !
						exit;
					}
					if( FOGCore::getClass('PostdownloadscriptManager')->count()>0 ) {
						for($i = 0;$i<128;$i++) {
							foreach ((array)self::getClass('PostdownloadscriptManager')
								->find(array('priority' => $i)) as &$PDSScript) {
									if ($PDSScript->get('quelimage') == $ImgID) {
										$NbrScriptTEMP++; // Incrémente de 1 à chaque fois qu'un script est dispo.
										if ($numeroduScript == $NbrScriptTEMP) {
												// ON a UN GAGNANT !! 
												// Ajout post déception ; FOG utilise (./common/init.php) un système de nettoyage des pages web à destinations
												//  des clients. Par conséquent, je ne PEUT PAS envoyer le script "en l'état" ; je suis obligé de le laisser en base64.
												//echo base64_decode($PDSScript->get('lescript'));
												echo $PDSScript->get('lescript');
												
												//echo "J'ai ".$PDSScript->get('name')." avec comme NbrScriptTEMP ".$NbrScriptTEMP;
										}
										
									}
								unset($PDSScript);
							}
						}
					} else {
						echo '#!err'."\n"; // Pas de scripts dispo
						exit;
					}
					// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ FIN MODE BALANCE DE SCRIPT ~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				}
            } catch (Exception $e) {			
                echo $e->getMessage();
            }
            break;
        }
    }
	
}
