<table style="border:1px; border-style:solid; border-color:red">
	<tr>
		<td style='border:1px; border-style:solid; border-color:red'>ID</td>
		<td style='border:1px; border-style:solid; border-color:red'>First Name</td>
		<td style='border:1px; border-style:solid; border-color:red'>Last Name</td>
		<td style='border:1px; border-style:solid; border-color:red'>Email</td>
	</tr>
	<?php
	require "../Tables-MakeDB/makeDBConnection.php";

	$sql = "SELECT id, firstname, lastname, email FROM Students";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			echo "<tr style='border:1px; border-style:solid; border-color:red'>";
			echo "<td style='border:1px; border-style:solid; border-color:red'>" . $row["id"] . "</td>";
			echo "<td style='border:1px; border-style:solid; border-color:red'>" . $row["firstname"] . "</td>";
			echo "<td style='border:1px; border-style:solid; border-color:red'>" . $row["lastname"] . "</td>";
			echo "<td style='border:1px; border-style:solid; border-color:red'>" . $row["email"] . "</td>";
			echo "</tr>";
		}
	} else {
		echo "0 results";
	}
	$conn->close();
	?>
</table>