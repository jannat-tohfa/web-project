<?php
require_once('../tcpdf/tcpdf.php');
include "../includes/dbconnection.php";

// Create a new PDF instance
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Your Name');
$pdf->SetAuthor('Your Organization');
$pdf->SetTitle('Appointment Details Report');
$pdf->SetSubject('Appointment Details Report');
$pdf->SetKeywords('TCPDF, Appointment Details, Report');

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Fetch appointment details using $_GET['aptnumber']
$cid = $_GET['aptnumber'];
$ret = mysqli_query($con, "SELECT tbluser.FirstName, tbluser.LastName, tbluser.Email, tbluser.MobileNumber, tblbook.ID AS bid, tblbook.AptNumber, tblbook.AptDate, tblbook.AptTime, tblbook.Message, tblbook.BookingDate, tblbook.Remark, tblbook.Status, tblbook.RemarkDate FROM tblbook JOIN tbluser ON tbluser.ID = tblbook.UserID WHERE tblbook.AptNumber='$cid'");

// Loop through the fetched data and generate PDF content
while ($row = mysqli_fetch_array($ret)) {
  $html = '
    <h4 style="text-align: center;color: blue;">Appointment Details</h4>
    <table class="table table-bordered">
        <tr>
            <th>Appointment Number</th>
            <td>' . $row['AptNumber'] . '</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>' . $row['FirstName'] . ' ' . $row['LastName'] . '</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>' . $row['Email'] . '</td>
        </tr>
        <tr>
            <th>Mobile Number</th>
            <td>' . $row['MobileNumber'] . '</td>
        </tr>
        <tr>
            <th>Appointment Date</th>
            <td>' . $row['AptDate'] . '</td>
        </tr>
        <tr>
            <th>Appointment Time</th>
            <td>' . $row['AptTime'] . '</td>
        </tr>
        <tr>
            <th>Apply Date</th>
            <td>' . $row['BookingDate'] . '</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>' . getStatusLabel($row['Status']) . '</td>
        </tr>
    </table>
    ';

  // Write the HTML content to the PDF
  $pdf->writeHTML($html, true, false, true, false, '');
}

// Close and output PDF document
$pdf->Output('appointment_details_report.pdf', 'I');

// Function to get the label for status
function getStatusLabel($status)
{
  if ($status == "") {
    return "Not Updated Yet";
  } elseif ($status == "Selected") {
    return "Selected";
  } elseif ($status == "Rejected") {
    return "Rejected";
  }
}
