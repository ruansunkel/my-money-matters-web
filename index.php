<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <title>Tactical Budgeting 4</title>

        <script src="https://www.gstatic.com/firebasejs/3.6.3/firebase.js"></script>
        <script>
            var config = {
                apiKey: "AIzaSyDduHfieTkSq1KFVKxpmnok3Dg9jtkFtgA",
                authDomain: "ruans-app-aa38f.firebaseapp.com",
                databaseURL: "https://ruans-app-aa38f.firebaseio.com",
                storageBucket: "ruans-app-aa38f.appspot.com",
                messagingSenderId: "935783809503"
            };
            firebase.initializeApp(config);

            firebase.auth().signInWithEmailAndPassword("rnsnkl@gmail.com", "P03p7129@firebase").catch(function(error) {
                alert("Error signing in");
            });
        </script>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    </head>
    <body>
        <div class="panel panel-default" style="margin: 20px">
            <div class="panel-heading">
                <h3 class="panel-title">Add Transaction</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                    Transaction
                                </label>
                                &nbsp;
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                    Transfer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Category">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Description">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
                            <div class="input-group">
                                <div class="input-group-addon">R</div>
                                <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Approved
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn btn-default" type="submit">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </body>
    
    <script type="text/javascript">
        function renderLog(log) {
            document.body.innerHTML = "";

            container = document.createElement("div");
            document.body.appendChild(container);

            field = document.createElement("input");
            field.id = "date";
            field.name = "date";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("input");
            field.id = "type";
            field.name = "type";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("input");
            field.id = "category";
            field.name = "category";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("input");
            field.id = "description";
            field.name = "description";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("input");
            field.id = "amount";
            field.name = "amount";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("input");
            field.id = "approved";
            field.name = "approved";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("input");
            field.id = "index";
            field.name = "index";
            field.type = "text";
            container.appendChild(field);

            field = document.createElement("div");
            field.innerHTML = "Add";
            container.appendChild(field);

            field.onclick = function() {
                entry = {
                    date: document.getElementById("date").value,
                    type: document.getElementById("type").value,
                    category: document.getElementById("category").value,
                    description: document.getElementById("description").value,
                    amount: parseFloat(document.getElementById("amount").value),
                    approved: document.getElementById("approved").value === "true"
                };

                log.splice(log.length-parseInt(document.getElementById("index").value), 0, entry);

                saveLog(log);
                renderLog(log);
            }

            // Start with beautiful Form

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

                // Approve Entry
                td = document.createElement("td");
                td.innerHTML = entry.approved ? "Disable" : "Approve";
                td.style.cursor = "pointer";
                tr.appendChild(td);
                tbody.appendChild(tr);

                td.onclick = function() {
                    entry = log[index];
                    entry.approved = !entry.approved;

                    log.splice(index, 1, entry);

                    saveLog(log);
                    renderLog(log);
                }

                // Remove Entry
                td = document.createElement("td");
                td.innerHTML = "Remove";
                td.style.cursor = "pointer";
                tr.appendChild(td);
                tbody.appendChild(tr);

                td.onclick = function() {
                    if (!window.confirm("Are you sure you want to remove this entry?")) {
                        return;
                    }

                    log.splice(index, 1);
                    saveLog(log);
                    renderLog(log);
                }
            });
        }

        function saveLog(log) {
            list = [];
            
            log.forEach(function(entry){
                list.push("{\"date\":\"" + entry.date + "\", \"type\":\"" + entry.type + "\", \"category\":\"" + entry.category + "\", \"description\":\"" + entry.description + "\", \"amount\":" + entry.amount + ", \"approved\":" + entry.approved + "}");
            });

            firebase.database().ref("Ruan").set({
                list: "[" + list.join() + "]"
            });
        }

        function retrieveAndRenderLog() {
            firebase.database().ref('Ruan').once("value").then(function(snapshot) {
                renderLog(JSON.parse(snapshot.child("list").val()));
            });
        }

        retrieveAndRenderLog();
    
    </script>
</html>
