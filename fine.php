
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
          .card{
            background-color: #697565;
            border: 0.2rem solid #181C14;
            padding: 0.2rem 0.2rem;
            min-height: 32rem;
            min-width: 28rem;
            border-radius: 0.6rem;
            box-shadow: 0.4rem 0.4rem 0.4rem rgb(66, 61, 54);
            display: flex;
            flex-direction: column;
            justify-content: start;
            align-items: center;
            font-size: 1.2rem;
          }
          .primary-btn{
            background-color: #3C3D37;
            width: 8rem;
            height: 2.6rem;
            border-radius: 0.4rem;
            color: #ECDFCC;
            transition: 0.3s ease-in-out;
        }
        
          .primary-btn:hover{
            background-color: #181C14;
            
          }
          .secondary-btn{
            background-color: #697565 ;
            height: 8rem;
            width: 18rem;
            font-size: 2.2rem;
            border-radius: 1rem;
            border: 0.2rem solid #181C14;
          }
          label{
            font-size: 1.2rem;
            font-weight: 600;
          }
          input{
            text-align: left;
            padding: 0.2rem 0.6rem;
            border-radius: 0.4rem;
            border: 0.1rem solid #181C14;
            background-color: #ECDFCC;
            color: #181C14;
            width: 70%;
            margin-bottom: 0.8rem;
          }
          ::placeholder{
            text-align: center;
          }
          body{
            color: #ECDFCC;
          }
         
         
        }
      </style>
</head>
<body class="bg-[#181C14] flex justify-center items-center">
    <!-- Fine -->
     <div class="flex items-center justify-center mt-[1rem]">
        <form action="./fine/fine_insert.php" method="post" class="card">
            <h1 class="text-4xl font-bold my-[1rem]">Fine</h1>
            <label for="id">ID</label>
            <input type="number" name="id" placeholder="ID">
          
            <label for="car">Car</label>
            <input type="text" name="car" placeholder="Car">
            
            <label for="person">Person</label>
            <input type="text" name="person" placeholder="Person">
          
            <label for="fine_date">Date</label>
            <input type="date" name="fine_date" placeholder="Date" class="w-[70%]">
         
            <label for="fine_amount">Amount</label>
            <input type="text" name="fine_amount" placeholder="Amount">
         
            <label for="fine_reason">Reason</label>
            <input type="text" name="fine_reason" placeholder="Write reason">
      
            <button class="primary-btn my-[2.8rem]" type="submit" name="fine_add" onclick="window.open('fine/fine_query.php')">Add</button>
        </form>
    </div>
    <div>
      <table>
        <?php
        
        if(isset($_POST['car_search_query'])){
          $search = $_POST['search'];

          $sql = "SELECT * from 'signup' where id ='$search'";
          $result = mysqli_query($conn, $sql);
          if($result){
            if (mysqli_num_rows($result) > 0){
              echo '<thead>
              <tr>
              <th> Sl no </th>
              <th>ID</th>
                <th>Car</th>
                <th>Person</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Edit</th>
                </tr>
                </thead>
              ';
              $row = mysqli_fetch_assoc($result);
              echo ' <table>
              
              <tr>
              <td>'.$row['id'].'</td>
              <td>'.$row['person_name'].'</td>
              <td>'.$row['person_address'].'</td>
              td>'.$row['phone_number'].'</td>
              
              </tr>
              ';
              
            }
          }
        }

        
        
        
        ?>
            
      

    </div>

    
</body>
</html>