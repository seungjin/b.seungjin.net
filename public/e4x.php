<?php
/**
 * Quick PHP MySQL E4X Query Tool
 * @author James Zimmerman II
 */


// MySQLi database pulled in.
$dbConnector = new mysqli("mysql.seungjin.net",'seungjin','chickfly81','seungjin_dev');

// Dirty to Clean arrays for key names
$dirty = array( "(", ")" );
$clean = array( "" );

// Use a DOMDocument object for strict output format.
$output = new DOMDocument( '1.0', 'UTF-8' );

// We'll need a root element container.
$root = $output->appendChild(
  $output->createElement( 'result' )
);

// Read our query from POST.
$sql = $_POST['query'];
$sql = "select * from subnet_view";

// Perform our query.
$dbResult = $dbConnector->query( $sql );

// For inserts we want to pull the inserts resulting
// ID as our result.
if( strtolower( substr( $sql, 0, 6 ) ) == "insert" ) {
  $dbResult = $dbConnector->query(
    "SELECT LAST_INSERT_ID() AS ID"
  );
}

// Loop our results and create the XML
if( $dbResult->num_rows < 1 ) {
  // Provide default for no result
  $resultMessage = $root->appendChild(
    $output->createElement( 'row' )
  );
  $resultMessage->setAttributeNode(
    new DOMAttr( "record", "0" )
  );
  $resultNotice = $resultMessage->appendChild(
    $output->createTextNode( "No valid results" )
  );
} else {
  // Process our result to XML output.
  for( $i=1; $i<=$dbResult->num_rows; $i++ ) {
    // Pull an associative array.
    $resultRow = $dbResult->fetch_assoc();
    // Create our row.
    $returnedData = $root->appendChild(
      $output->createElement( 'row' )
    );
    // Mark it with a particular ID.
    $returnedData->setAttributeNode(
      new DOMAttr( "record", $i )
    );
    // Process the row's data.
    foreach( $resultRow as $key=>$value ) {
      // Create a data chunk.
      $returnedData->setAttributeNode(
        new DOMAttr(
          // Attribute
          str_replace( $dirty, $clean, $key ),
          // Value
          $value
        )
      );
    } //foreach
  } //for
} //else

// Close our database connection.
$dbConnector->close();

// Echo our generated output.
echo $output->saveXML();

?>