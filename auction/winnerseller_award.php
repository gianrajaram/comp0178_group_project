<?php include("database_connection.php");

while (true) {
    echo "Begin closing auction process.";
    // Notify all sellers whose auctions have ended in the past 1 min and their winners
    $query = "SELECT A.auctionName, A.auctionReservePrice, U.userEmail AS sellerEmail, U.userFirstName AS sellerFirstName, MAX(B.bidValue) AS highestBid, UB.userEmail AS highestBuyerEmail, UB.userFirstName AS highestBuyerFirstName 
                FROM 
                    Auctions A 
                JOIN 
                    Users U ON A.sellerID = U.userID 
                LEFT JOIN 
                    Bids B ON A.auctionID = B.auctionID AND B.bidValue = (SELECT MAX(bidValue) FROM Bids WHERE auctionID = A.auctionID)
                LEFT JOIN 
                    Users UB ON B.buyerID = UB.userID 
                WHERE 
                    A.auctionEndDate <= NOW() 
                    AND A.auctionEndDate >= NOW() - INTERVAL 5 MINUTE 
                GROUP BY 
                    A.auctionID 
                ORDER BY 
                    highestBid DESC";
    $result = send_query($query);

    // In case there are auctions that have ended in the past 5 minutes
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $emailSeller = $row['sellerEmail'];
            $firstNameSeller = $row['sellerFirstName'];
            $auctionTitle = $row['auctionName'];
            $emailHighestBuyer = $row['highestBuyerEmail'];
            $firstNameHighestBuyer = $row['highestBuyerFirstName'];
            $auctionReservePrice = $row['auctionReservePrice'];
            $highestBid = $row['highestBid'];

            // Successful auction - when highest bid is higher than reserve price
            if ($highestBid >= $auctionReservePrice and $highestBid != null) {
                // seller email
                $name = "ReWear Auctions"; //sender’s name
                $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
                $recipient = $emailSeller; //recipient
                $mail_body= "$firstNameSeller, your auction $auctionTitle was successfully closed! :)"; //mail body
                $subject = "ReWear Auctions - Successful closure of auction"; //subject
                $header = "From: ". $name . " <" . $email . ">\r\n";
                mail($recipient, $subject, $mail_body, $header);
                // buyer email
                $name = "ReWear Auctions"; //sender’s name
                $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
                $recipient = $emailHighestBuyer; //recipient
                $mail_body= "$firstNameHighestBuyer, you won auction $auctionTitle with highest bid £$highestBid! :)"; //mail body
                $subject = "ReWear Auctions - Successful closure of auction"; //subject
                $header = "From: ". $name . " <" . $email . ">\r\n";
                mail($recipient, $subject, $mail_body, $header);  
            }
            // Unsuccessful auction - when highest bid is lower than reserve price
            else if ($highestBid < $auctionReservePrice and $highestBid != null) {
                // seller email
                $name = "ReWear Auctions"; //sender’s name
                $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
                $recipient = $emailSeller; //recipient
                $mail_body= "$firstNameSeller, your auction $auctionTitle was not successfully closed! No bids above the reserve price! :("; //mail body
                $subject = "ReWear Auctions - Unsuccessful closure of auction"; //subject
                $header = "From: ". $name . " <" . $email . ">\r\n";
                mail($recipient, $subject, $mail_body, $header);    
            }
            // Unsuccessful auction - when there are no bids
            else if ($highestBid == null) {
                // seller email
                $name = "ReWear Auctions"; //sender’s name
                $email = "UCL2023DatabasesAuctionReWear@gmail.com"; //sender’s e-mail address
                $recipient = $emailSeller; //recipient
                $mail_body= "$firstNameSeller, your auction $auctionTitle was not successfully closed! No one submitted any bids! :("; //mail body
                $subject = "ReWear Auctions - Unsuccessful closure of auction"; //subject
                $header = "From: ". $name . " <" . $email . ">\r\n";
                mail($recipient, $subject, $mail_body, $header); 
            }
            }
            echo "Finished process to close auctions.";
        
    } 
        else {
        echo "No auctions have ended in the past 5 minutes.";
        }
    
    sleep(300); // Sleep for 5 minutes 
}


?>
