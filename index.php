<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <title>Tactical Budgeting 4</title>

        <script src="https://www.gstatic.com/firebasejs/3.6.3/firebase.js"></script>
        <script>
            // Initialize Firebase
            var config = {
                apiKey: "AIzaSyDduHfieTkSq1KFVKxpmnok3Dg9jtkFtgA",
                authDomain: "ruans-app-aa38f.firebaseapp.com",
                databaseURL: "https://ruans-app-aa38f.firebaseio.com",
                storageBucket: "ruans-app-aa38f.appspot.com",
                messagingSenderId: "935783809503"
            };
            firebase.initializeApp(config);

            firebase.auth().signInWithEmailAndPassword("rnsnkl@gmail.com", "P03p7129@firebase").catch(function(error) {});

            // Import Transactions
            // var log = <?php //echo file_get_contents("ruan.json"); ?>;

            // list = [];
            // log.forEach(function(entry){
            //     list.push("{\"date\":\"" + entry.date + "\", \"type\":\"" + entry.type + "\", \"category\":\"" + entry.category + "\", \"description\":\"" + entry.description + "\", \"amount\":" + entry.amount + ", \"approved\":" + entry.approved + "}");
            // });

            // firebase.database().ref("Ruan").set({
            //     list: "[" + list.join() + "]"
            // });

            

        </script>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    </head>
    <body>
    </body>
    
    <script type="text/javascript">
        function renderLog(log) {
            // Get distinct categories
            categories = {};
            log.forEach(function(entry){
                categories[entry.category] = true;
            });
            
            container = document.createElement("div");
            container.className = "page-header";
            container.style.margin = "20px";
            document.body.appendChild(container);

            header = document.createElement("h1");
            header.innerHTML = "Current View";
            container.appendChild(header);
            
            container = document.createElement("div");
            container.style.width = "30%";
            container.style.margin = "20px";
            document.body.appendChild(container);
            
            table = document.createElement("table");
            table.className = "table table-striped table-bordered";
            container.appendChild(table);
            
            thead = document.createElement("thead");
            table.appendChild(thead);
            
            tr = document.createElement("tr");
            thead.appendChild(tr);
            
            th = document.createElement("th");
            th.innerHTML = "Category";
            tr.appendChild(th);
            
            th = document.createElement("th");
            th.innerHTML = "Available";
            tr.appendChild(th);
            
            tbody = document.createElement("tbody");
            table.appendChild(tbody);
        
            Object.keys(categories).sort().forEach(function(key){
                tr = document.createElement("tr");
                
                td = document.createElement("td");
                tr.appendChild(td);
                td.innerHTML = key;
                
                var initial = log.reduce(function(accumulator, entry){
                    return accumulator
                    + ((entry.category === key && entry.type === "Transfer" && entry.approved) ? entry.amount : 0)
                    + ((entry.category === key && entry.type === "Transaction" && entry.approved && entry.amount > 0) ? entry.amount : 0);
                }, 0);
                initial = Math.round(initial * 100) / 100;
                
                var spent = log.reduce(function(accumulator, entry){
                    return accumulator + ((entry.category === key && entry.type === "Transaction" && entry.approved && entry.amount < 0) ? entry.amount : 0);
                }, 0);
                spent = Math.round(spent * 100) / 100;
                
                td = document.createElement("td");
                tr.appendChild(td);
                td.innerHTML = (Math.round((initial + spent) * 100) / 100).toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' });
                
                if (initial === initial + spent) {
                    tr.className = "success";
                }
                if (initial + spent === 0) {
                    tr.className = "danger";
                }

                if (initial + spent > 0) {
                    tbody.appendChild(tr);
                }
            });
            
            // Available Balance
            tr = document.createElement("tr");
            td = document.createElement("td");
            tr.appendChild(td);
            b = document.createElement("b");
            b.innerHTML = "TOTAL";
            td.appendChild(b);
            
            var initial = log.reduce(function(accumulator, entry){
                return accumulator + ((entry.type === "Transfer" && entry.approved) ? entry.amount : 0);
            }, 0);
            initial = Math.round(initial * 100) / 100;
            
            var spent = log.reduce(function(accumulator, entry){
                return accumulator + ((entry.type === "Transaction" && entry.approved) ? entry.amount : 0);
            }, 0);
            spent = Math.round(spent * 100) / 100;
            
            td = document.createElement("td");
            td.innerHTML = "<b>" + (Math.round((initial + spent) * 100) / 100).toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' }) + "</br>";
            tr.appendChild(td);
            
            tbody.appendChild(tr);
            
            // Unaccounted Balance
            tr = document.createElement("tr");
            td = document.createElement("td");
            tr.appendChild(td);
            b = document.createElement("b");
            b.innerHTML = "FLOATING";
            td.appendChild(b);
            
            var transfers = log.reduce(function(accumulator, entry){
                return accumulator + ((entry.type === "Transfer") ? entry.amount : 0);
            }, 0);
            transfers = Math.round(initial * 100) / 100;
            
            td = document.createElement("td");
            td.innerHTML = "<b>" + Math.abs(transfers).toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' }) + "</br>";
            tr.appendChild(td);
            
            tbody.appendChild(tr);
            
            // Transaction List
            container = document.createElement("div");
            container.className = "page-header";
            container.style.margin = "20px";
            document.body.appendChild(container);

            header = document.createElement("h1");
            header.innerHTML = "Transaction Sheet";
            container.appendChild(header);
            
            container = document.createElement("div");
            container.style.width = "90%";
            container.style.margin = "20px";
            document.body.appendChild(container);
            
            table = document.createElement("table");
            table.className = "table table-striped table-bordered";
            container.appendChild(table);
            
            thead = document.createElement("thead");
            table.appendChild(thead);
            
            tr = document.createElement("tr");
            thead.appendChild(tr);
            
            th = document.createElement("th");
            tr.appendChild(th);
            th = document.createElement("th");
            th.innerHTML = "Date";
            tr.appendChild(th);
            th = document.createElement("th");
            th.innerHTML = "Type";
            tr.appendChild(th);
            th = document.createElement("th");
            th.innerHTML = "Category";
            tr.appendChild(th);
            th = document.createElement("th");
            th.innerHTML = "Description";
            tr.appendChild(th);
            th = document.createElement("th");
            th.innerHTML = "Result";
            tr.appendChild(th);
            
            var tbody = document.createElement("tbody");
            table.appendChild(tbody);
            
            log.forEach(function(entry, index){
                var tr = document.createElement("tr");
                var td = document.createElement("td");
                
                tr.appendChild(td);
                tbody.appendChild(tr);
                
                td.innerHTML = log.length - index;
                
                td = document.createElement("td");
                tr.appendChild(td);
                tbody.appendChild(tr);
                td.innerHTML = entry.date;
                
                td = document.createElement("td");
                tr.appendChild(td);
                tbody.appendChild(tr);
                if (entry.amount > 0) {
                    td.innerHTML = entry.amount.toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' }) + (entry.approved ? " got " : " will get ") + (entry.type === "Transfer" ? "moved" : "paid") + " into ";
                }
                else{
                    td.innerHTML = Math.abs(entry.amount).toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' }) + (entry.approved ? " got " : " will get ") + (entry.type === "Transfer" ? "moved" : "paid") + " out of ";
                }
                        
                td = document.createElement("td");
                tr.appendChild(td);
                tbody.appendChild(tr);
                td.innerHTML = entry.category;
                        
                td = document.createElement("td");
                tr.appendChild(td);
                tbody.appendChild(tr);
                td.innerHTML = entry.description;
                        
                balance = 0;
                for (i = log.length - 1; i >= index; i--) {
                    if (entry.category === log[i].category) {
                        balance += log[i].amount;
                    }
                }
                balance = Math.round(balance * 100) / 100;
                
                previous = (balance - entry.amount);
                previous = Math.round(previous * 100) / 100;
                
                td = document.createElement("td");
                tr.appendChild(td);
                tbody.appendChild(tr);
                td.innerHTML = entry.category + (entry.approved ? " went " : " will go ") + "from " + previous.toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' }) + " to " + balance.toLocaleString('en-ZA', { style: 'currency', currency: 'ZAR' })
                
                if (balance < 0) {
                    tr.className = "danger";
                }
                
                if (entry.amount > 0 && entry.type === "Transaction") {
                    tr.className = "success";
                }

                if (!entry.approved) {
                    tr.className = "info";
                }
            });
        }

        firebase.database().ref('Ruan').once("value").then(function(snapshot) {
            renderLog(JSON.parse(snapshot.child("list").val()));
        });
    
    </script>
</html>
