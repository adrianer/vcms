<?php
/*
This file is part of VCMS.

VCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

VCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with VCMS. If not, see <http://www.gnu.org/licenses/>.
*/

if(!is_object($libGlobal) || !$libAuth->isLoggedin())
	exit();


echo '<h1>Versand der Nachricht</h1>';
echo '<h2>DIESE SEITE NICHT SCHLIESSEN BEVOR ALLE NACHRICHTEN ERFOLGREICH VERSENDET WORDEN SIND!</h2>';

$stmt = $libDb->prepare('SELECT email FROM base_person WHERE id=:id');
$stmt->bindValue(':id', $libAuth->getId(), PDO::PARAM_INT);
$stmt->execute();
$stmt->bindColumn('email', $email);
$stmt->fetch();


if(!isset($_POST['nachricht']) || $_POST['nachricht'] == '' || !isset($_POST['subject'])){
	$libGlobal->errorTexts[] = 'Es wurde kein Nachrichtentext eingegeben.';
} else {
	$subjectGroups = array();

	if(isset($_POST['fuchsia']) && $_POST['fuchsia'] == 'on'){
		$subjectGroups[] = 'Füchse';
	}

	if(isset($_POST['burschen']) && $_POST['burschen'] == 'on'){
		$subjectGroups[] = 'Burschen';
	}

	if(isset($_POST['ahah_interessiert']) && $_POST['ahah_interessiert'] == 'on' && (!isset($_POST['ahah']) || $_POST['ahah'] != 'on')){
		$subjectGroups[] = 'Int. AHAH';
	}

	if(isset($_POST['ahah']) && $_POST['ahah'] == 'on'){
		$subjectGroups[] = 'AHAH';
	}

	if(isset($_POST['hausbewohner']) && $_POST['hausbewohner'] == 'on'){
		$subjectGroups[] = 'Hausbewohner';
	}

	if(isset($_POST['couleurdamen']) && $_POST['couleurdamen'] == 'on'){
		$subjectGroups[] = 'Couleurdamen';
	}

	if(isset($_POST['gattinnen_interessiert']) && $_POST['gattinnen_interessiert'] == 'on' && (!isset($_POST['gattinnen']) || $_POST['gattinnen'] != 'on')){
		$subjectGroups[] = 'Int. Gattinnen';
	}

	if(isset($_POST['gattinnen']) && $_POST['gattinnen'] == 'on'){
		$subjectGroups[] = 'Gattinnen';
	}

	if(count($subjectGroups) == 0){
		$libGlobal->errorTexts[] = 'Es wurde keine Adressatengruppe ausgewählt.';
	}

	$subjectGroupsString = '[' .implode(', ', $subjectGroups). '] ';
	$subjectRegionsString = '';

	if($_POST['region'] != '' && $_POST['region'] != 'NULL'){
		$stmt = $libDb->prepare('SELECT bezeichnung FROM base_region WHERE id=:id');
		$stmt->bindValue(':id', $_POST['region'], PDO::PARAM_INT);
		$stmt->execute();
		$stmt->bindColumn('bezeichnung', $region);
		$stmt->fetch();

		if($region != ''){
			$subjectRegionsString = '[' .$region. '] ';
		}
	}

	/*
	* build subject
	*/
	$subject = '[' .$libConfig->verbindungName. '] ' .$subjectGroupsString . $subjectRegionsString . $_POST['subject'];

	/*
	* start output
	*/
	echo '<p class="mb-4">' .$libString->protectXss($subject). '</p>';
	echo '<p class="mb-4">' .nl2br($libString->protectXss($_POST['nachricht'])). '</p>';

	/*
	* build and send mail
	*/
	$sqlGroups = array();

	if(isset($_POST['fuchsia']) && $_POST['fuchsia'] == 'on'){
		$sqlGroups[] = "gruppe='F'";
	}

	if(isset($_POST['burschen']) && $_POST['burschen'] == 'on'){
		$sqlGroups[] = "gruppe='B'";
	}

	if(isset($_POST['ahah_interessiert']) && $_POST['ahah_interessiert'] == 'on'){
		$sqlGroups[] = "(gruppe = 'P' AND interessiert = 1)";
	}

	if(isset($_POST['ahah']) && $_POST['ahah'] == 'on'){
		$sqlGroups[] = "gruppe='P'";
	}

	if(isset($_POST['hausbewohner']) && $_POST['hausbewohner'] == 'on'){
		$sqlGroups[] = "((gruppe='F' OR gruppe='B') AND plz1=:plz AND strasse1 LIKE :street)";
	}

	if(isset($_POST['couleurdamen']) && $_POST['couleurdamen'] == 'on'){
		$sqlGroups[] = "gruppe='C'";
	}

	if(isset($_POST['gattinnen_interessiert']) && $_POST['gattinnen_interessiert'] == 'on'){
		$sqlGroups[] = "((gruppe='G' OR gruppe='W') AND interessiert = 1)";
	}

	if(isset($_POST['gattinnen']) && $_POST['gattinnen'] == 'on'){
		$sqlGroups[] = "(gruppe='G' OR gruppe='W')";
	}

	$sqlGroupsString = ' AND ('.implode(' OR ',$sqlGroups).') ';

	//evaluate regional restrictions
	$regionString = '';

	if($_POST['region'] != '' && $_POST['region'] != 'NULL'){
		$regionString = " AND (region1=:region OR region2=:region) ";
	}

	//build array of receivers
	$recipientsArray = array();

	//add receivers
	$sql = "SELECT anrede, titel, rang, vorname, praefix, name, suffix, email FROM base_person, mod_rundbrief_empfaenger WHERE base_person.id = mod_rundbrief_empfaenger.id AND email != '' AND email IS NOT NULL AND empfaenger=1 ".$regionString.$sqlGroupsString ." AND gruppe != 'X' AND gruppe != 'T' AND gruppe != 'V' ORDER BY name";
	$stmt = $libDb->prepare($sql);

	if($regionString != ''){
		$stmt->bindValue(':region', $_POST['region'], PDO::PARAM_INT);
	}

	if(isset($_POST['hausbewohner']) && $_POST['hausbewohner'] == 'on'){
		$streetNormalized = $libString->normalizeStreet($libConfig->verbindungStrasse);

		$stmt->bindValue(':plz', $libConfig->verbindungPlz);
		$stmt->bindValue(':street', '%' .$streetNormalized. '%');
	}

	$stmt->execute();

	$i = 0;

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$recipientsArray[$i][0] = $row['email'];
		$recipientsArray[$i][1] = $libPerson->formatNameString($row['anrede'], $row['titel'], $row['rang'], $row['vorname'], $row['praefix'], $row['name'], $row['suffix'], 0);
		$i++;
	}

	//add Fuchsmajor
	if(isset($_POST['fuchsia']) && $_POST['fuchsia'] == 'on' && (!isset($_POST['burschen']) || $_POST['burschen'] != 'on')){
		$vorstand = $libAssociation->getAnsprechbarerAktivenVorstandIds();

		$stmt = $libDb->prepare("SELECT anrede, titel, rang, vorname, praefix, name, suffix, email FROM base_person, mod_rundbrief_empfaenger WHERE (base_person.id = :fuchsmajor OR base_person.id = :fuchsmajor2) AND base_person.id = mod_rundbrief_empfaenger.id AND gruppe != 'X' AND gruppe != 'T' AND gruppe != 'V' AND empfaenger=1");
		$stmt->bindValue(':fuchsmajor', $vorstand['fuchsmajor'], PDO::PARAM_INT);
		$stmt->bindValue(':fuchsmajor2', $vorstand['fuchsmajor2'], PDO::PARAM_INT);
		$stmt->execute();

		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($row['email'] != ''){
				$recipientsArray[$i][0] = $row['email'];
				$recipientsArray[$i][1] = $libPerson->formatNameString($row['anrede'], $row['titel'], $row['rang'], $row['vorname'], $row['praefix'], $row['name'], $row['suffix'], 0);
				$i++;
			}
		}
	}

	if(count($recipientsArray) <= 0) {
		echo '<p class="mb-4">Es wurden 0 Adressaten ausgewählt. Es wird keine E-Mail versendet.</p>';
	}

	//attachement
	$attachementFile = '';
	$attachementName = '';

	if(isset($_FILES['anhang']) && isset($_FILES['anhang']['tmp_name']) && isset($_FILES['anhang']['name'])){
		$attachementFile = $_FILES['anhang']['tmp_name'];
		$attachementName = $_FILES['anhang']['name'];
	}

	$recipientsPerMail = 15;
	$numberOfMails = ceil(count($recipientsArray) / $recipientsPerMail);

	if($numberOfMails > 1) {
		echo '<p class="mb-4">Es wurden mehr als 15 Adressaten ausgewählt. Die E-Mail wird in '.$numberOfMails.' E-Mails aufgeteilt mit je max. 15 Adressaten pro E-Mail.</p>';
	}

	for($j=0; $j<$numberOfMails; $j++){
		$mailNumber = $j + 1;
		$subRecipientsArray = array_slice($recipientsArray, $j*$recipientsPerMail, $recipientsPerMail);

		echo '<hr />';
		echo '<p class="mb-4">Sende E-Mail (' .$mailNumber. '/' .$numberOfMails. ')';

		if(is_file($attachementFile)){
			echo ' mit Anhang';
		}

		echo ' an:</p>';
		echo '<p class="mb-4">';

		foreach($subRecipientsArray as $recipient){
			echo $recipient[1]. ' &lt;' .$recipient[0]. '&gt;<br />';
		}

		echo '</p>';

		sendMail(
			$libPerson->formatNameString($libAuth->getAnrede(), $libAuth->getTitel(), '', $libAuth->getVorname(), $libAuth->getPraefix(), $libAuth->getNachname(), $libAuth->getSuffix(), 4),
			$subject, $email, $_POST['nachricht'], $subRecipientsArray, $attachementFile, $attachementName);
	}
}

echo $libString->getErrorBoxText();
echo $libString->getNotificationBoxText();


function sendMail($fromName, $subject, $replyEmail, $message, $recipientsArray, $attachementFile, $attachementName){
	global $libAuth, $libMail;

	$mail = $libMail->createPHPRundbriefMailer($fromName);

	$mail->Subject = $subject;
	$mail->isHTML(false);
	$mail->addReplyTo($replyEmail);
	$mail->Body = stripslashes($message);

	if(!istImVorstand($libAuth->getAemter())){
		// low priority
		$mail->Priority = 5;
	}

	echo '<p class="mb-4">DEBUG: Adding sender '.$replyEmail.' to the To-field, so that he/she gets a copy in any case.</p>';
	$mail->addAddress($replyEmail, $fromName);

	foreach($recipientsArray as $recipient){
		//echo '<p class="mb-4">DEBUG: Adding BCC: '.$recipient[0].'</p>';
		$mail->addBCC($recipient[0]);
	}

	if(is_file($attachementFile)){
		$mail->addAttachment($attachementFile, $attachementName);
	}

	try {
		if(!$mail->send()){
			echo '<p class="mb-4">Fehler beim Versand: ' .$mail->ErrorInfo. '</p>';
			echo '<p class="mb-4">E-Mail wahrscheinlich nicht versendet!</p>';
		} else {
			echo '<p class="mb-4">E-Mail Versand erfolgreich</p>';
		}
	} catch (Exception $exc) {
		echo '<p class="mb-4">Kritischer Fehler beim Versand: ' .$exc->getMessage(). '</p>';
		echo '<p class="mb-4">E-Mail wahrscheinlich nicht versendet!</p>';
	}

}

function istImVorstand($aemter){
	if(!is_array($aemter)){
		return false;
	}

	$vorstandsAemter = array('senior', 'consenior', 'fuchsmajor', 'fuchsmajor2', 'scriptor', 'quaestor', 'jubelsenior', 'ahv_senior', 'ahv_consenior', 'ahv_keilbeauftragter', 'ahv_scriptor', 'ahv_quaestor');
	$vorstandsAemterOfPerson = array_intersect($aemter, $vorstandsAemter);

	if(count($vorstandsAemterOfPerson) > 0){
		return true;
	} else {
		return false;
	}
}
