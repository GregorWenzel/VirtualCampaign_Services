<?php
/** 
 * ----------------------------------------------------------------------------
 * File: 		locale.de_DE.class.php
 * 
 * @author  	Christiane Kuhn <kuhn@webdevelopment-koeln.de>
 * @link 		http://webdevelopment-koeln.de
 * 
 * @category	Virtual Campaign 
 *
 * 
 * This Script was developed by Christiane Kuhn - ChK Web-Development.
 * You are authorised to use this Script on your internet sides.
 * The right to use applies only to the direct use of the software by HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 * You are not authorised to resale, to lease or to publish the software outside of HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 *
 * The passing of this script is not allowed.
 *
 * Please do not remove this header.
 *
 * ----------------------------------------------------------------------------
 * Rev: 	2012-01 	ChK	Created
 * 
 * ----------------------------------------------------------------------------
 *
 */

/**
 * Locale class 
 * 
 *
 */
class locale_lang
{
	/**
	 * error msgs
	 */
	const ERR_NOT_ALL_PARAMS = 'Nicht alle Pflichtfelder sind angegeben oder haben das falsche Format. Bitte korrigieren Sie Ihre Eingaben und versuchen Sie es erneut.'; 
	const ERR_MAXSTRINGLEN = 'Maximale Länge';
	const ERR_MINSTRINGLEN = 'Minimale Länge';
	const ERR_INT = 'Bitte nur numerische Werte eingeben';
	const ERR_DECIMAL = 'Bitte nur Dezimalwerte eingeben ';
	const ERR_DECIMAL_EXACT = 'Bitte nur max. Dezimalwerte eingeben ';
	const ERR_DATE = 'Bitte gültiges Datum eingeben';
	const ERR_TIME = 'Bitte gültige Zeit eingeben';
	const ERR_EMAIL = 'Bitte gültige Email eingeben';
	const ERR_MUSTHAVE = 'Bitte ausfüllen';
	const ERR_DEFAULT= 'Bitte gültige Werte/Formate eingeben';
	const ERR_PW = 'Das Passwort ist ungültig';
	const ERR_USER_UNIQUE = 'Der Benutzername ist schon vergeben.';
	const ERR_GROUP_UNIQUE = 'Der Gruppenname ist schon vergeben.';
	const ERR_EMAIL_UNIQUE = 'Die Emailadresse ist bereits registriert.'; 
	const ERR_LOGIN_SELECT = 'Ihre Logindaten wurden nicht gefunden.';
	/*
    const CHK_WRONG_FORMAT = 'Die Datei hat das falsche Format.';
    const CHK_NO_FILES = 'Bitte mindestens eine Datei hochladen.';
    const CHK_FILE_TOO_BIG = 'Die Datei ist zu groß.';
    const CHK_MAX_8_UPLOAD = 'Sie haben bereits die maximale Anzahl von acht Bildern hochgeladen.';
    const CHK_FILE_COPY_ERR ='Beim Upload der Datei ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.';
	*/
	
	const DELETE_REMINDER_MAIL_SUBJECT = 'TODO: subject noch zu definieren';
}
?>