<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Query</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
         @layer utilities{
            nav{
                color: #ECDFCC;
            }
            footer{
                color: #ECDFCC;
                margin-bottom: 5rem;
                
            }
            .primary-btn{
            background-color: #3C3D37;
            width: 8rem;
            height: 2.6rem;
            border-radius: 0.4rem;
            color: #ECDFCC;
            transition: 0.3s ease-in-out;
            font-size: 1.6rem;
            text-align: center;
            
        }       
          .primary-btn:hover{
            background-color: #181C14;   
            color: #ECDFCC;         
          }
                   
          body{
            color: #181C14;
            margin: 0.8rem 8rem;
            
            
          }
          .fine_display{
            display: flex;
            flex-direction: column;
            justify-content: space-between;
           
            margin-top: 4rem;
          }
          table{
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.4rem;
            margin: 6rem auto;
          }
          tr{
            border: 0.2rem solid #181C14;
        }
        th{
            padding: 0.8rem 1rem;
            border: 0.2rem solid #181C14;
            color: red;
            font-weight: 900;
          }
          td{
            border: 0.2rem solid #181C14;
            text-align: center;
            padding: 0.8rem 1rem;
          }
          h2{
            font-size: 2rem;
            text-align: center;
            margin-bottom: -4rem;
            font-weight: bolder;
          }       
          
         }
    </style>
    
</head>
<body>
    <?php include("../assests/navbar.php"); ?>
    <div class="fine_display">
      <h2>Fine Query Results:</h2>
      <table>
          <tr>
              <th>ID</th>
              <th>Car</th>
              <th>Person</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Reason</th>
              <th>Edit</th>
              
          </tr>
    
<?php 
include("../connectdb.php");

$sql = "SELECT * FROM fine";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" 
                . htmlspecialchars($row["car"]) . "</td><td>"
                . htmlspecialchars($row["person"]) . "</td><td>"
                . htmlspecialchars($row["fine_date"]) . "</td><td>"
                . htmlspecialchars($row["fine_amount"]) . "</td><td>"
                . htmlspecialchars($row["fine_reason"]) . "</td><td>
                <a href='update'>Update</a> <a href='delete'>&nbsp;&nbsp;&nbsp;Delete</a>
                </td></tr>";
        }
    } else {
        echo "No results";
    }
} else {
    die("Query failed: " . $conn->error);
}

// Close connection
$conn->close();
?>

      </table>


      <?php include("../assests/footer.php"); ?>
    </div>
</body>
</html>