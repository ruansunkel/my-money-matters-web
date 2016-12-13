<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <title>Tactical Budgeting</title>

        <!--Include Bootstrap CSS-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />

        <!--Include jQuery Theme-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        
        <!--Include jQuery JS-->
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

        <!--Include jQueryUI JS-->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js" integrity="sha256-DI6NdAhhFRnO2k51mumYeDShet3I8AKCQf/tf7ARNhI=" crossorigin="anonymous"></script>

        <!--Include Firebase JS-->
        <script type="text/javascript" src="https://www.gstatic.com/firebasejs/3.6.3/firebase.js"></script>
        
        <!--Initialize Firebase-->
        <script type="text/javascript">
            firebase.initializeApp({
                apiKey: "AIzaSyDduHfieTkSq1KFVKxpmnok3Dg9jtkFtgA",
                authDomain: "ruans-app-aa38f.firebaseapp.com",
                databaseURL: "https://ruans-app-aa38f.firebaseio.com",
                storageBucket: "ruans-app-aa38f.appspot.com",
                messagingSenderId: "935783809503"
            });
        </script>
    </head>

    <body>
    </body>
    
    <script type="text/javascript">
        user = "";

        function showLogin() {
            document.body.innerHTML = "";

            panel = document.createElement("div");
            panel.className = "panel panel-default";
            panel.style.width = "300px";
            panel.style.margin = "40px auto 0px auto";
            document.body.appendChild(panel);

            panelHeading = document.createElement("div");
            panelHeading.className = "panel-heading";
            panel.appendChild(panelHeading);

            panelTitle = document.createElement("h3");
            panelTitle.className = "panel-title";
            panelTitle.innerHTML = "Login";
            panelHeading.appendChild(panelTitle);

            panelBody = document.createElement("div");
            panelBody.className = "panel-body";
            panel.appendChild(panelBody);

            // Username
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Email";
            field.id = "username";
            field.type = "text";

            // Password
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Password";
            field.id = "password";
            field.type = "password";

            // Login Button
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("button");
            formGroup.appendChild(field);

            field.className = "btn btn-default";
            field.innerHTML = "Login";
            field.onclick = function() {
                field.setAttribute("disabled", true);
                document.getElementById("username").setAttribute("disabled", true);
                document.getElementById("password").setAttribute("disabled", true);
                panelTitle.innerHTML = "Please wait..."

                firebase.auth().signInWithEmailAndPassword(document.getElementById("username").value, document.getElementById("password").value).then(function() {
                    user = document.getElementById("username").value.split("@").join("_at_").split(".").join("_dot_");
                    retrieveAndRenderLog(user);
                }, function(error){
                    field.removeAttribute("disabled");
                    document.getElementById("username").removeAttribute("disabled");
                    document.getElementById("password").removeAttribute("disabled");
                    panelTitle.innerHTML = "Login";
                });
            }
        }

        showLogin();

        function renderLog(log) {
            document.body.innerHTML = "";

            // Start with beautiful Form
            var splitView = document.createElement("div");
            splitView.className = "row";
            document.body.appendChild(splitView);

            leftView = document.createElement("div");
            leftView.className = "col-md-6";
            splitView.appendChild(leftView);

            rightView = document.createElement("div");
            rightView.className = "col-md-6";
            splitView.appendChild(rightView);

            // Render Current View
            categories = {};
            log.forEach(function(entry){
                categories[entry.category] = true;
            });
            
            panel = document.createElement("div");
            panel.className = "panel panel-default";
            panel.style.margin = "40px";
            leftView.appendChild(panel);

            panelHeading = document.createElement("div");
            panelHeading.className = "panel-heading";
            panel.appendChild(panelHeading);

            panelTitle = document.createElement("h3");
            panelTitle.className = "panel-title";
            panelTitle.innerHTML = "Current View";
            panelHeading.appendChild(panelTitle);

            panelBody = document.createElement("div");
            panelBody.className = "panel-body";
            panel.appendChild(panelBody);
            
            // Current View
            table = document.createElement("table");
            table.className = "table table-striped table-bordered";
            panelBody.appendChild(table);
            
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


            // Add Transaction Form
            panel = document.createElement("div");
            panel.className = "panel panel-default";
            panel.style.margin = "40px";
            rightView.appendChild(panel);

            panelHeading = document.createElement("div");
            panelHeading.className = "panel-heading";
            panel.appendChild(panelHeading);

            panelTitle = document.createElement("h3");
            panelTitle.className = "panel-title";
            panelTitle.innerHTML = "Add Transaction";
            panelHeading.appendChild(panelTitle);

            panelBody = document.createElement("div");
            panelBody.className = "panel-body";
            panel.appendChild(panelBody);

            // Date
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Date";
            field.id = "date";
            field.name = "date";
            field.type = "text";

            $("#date").datepicker({dateFormat: "yy-mm-dd"});

            // Type
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            radio = document.createElement("div");
            radio.className = "radio";
            formGroup.appendChild(radio);

            label = document.createElement("label");
            radio.appendChild(label);

            field = document.createElement("input");
            label.appendChild(field);

            field.className = "radio";
            field.value = "Transaction";
            field.type = "radio";
            field.name = "type";
            field.id = "typeTransaction";
            field.setAttribute("checked", true);
            label.innerHTML = label.innerHTML + "Transaction";

            radio.innerHTML = radio.innerHTML + "&nbsp;&nbsp;";

            label = document.createElement("label");
            radio.appendChild(label);

            field = document.createElement("input");
            label.appendChild(field);

            field.className = "radio";
            field.value = "Transfer";
            field.type = "radio";
            field.name = "type";
            field.id = "typeTransfer";
            label.innerHTML = label.innerHTML + "Transfer";

            // Category
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Category";
            field.id = "category";
            field.name = "category";
            field.type = "text";

            // Description
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Description";
            field.id = "description";
            field.name = "description";
            field.type = "text";

            // Amount
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            inputGroup = document.createElement("div");
            inputGroup.className = "input-group";
            formGroup.appendChild(inputGroup);

            inputGroupAddon = document.createElement("div");
            inputGroupAddon.className = "input-group-addon";
            inputGroupAddon.innerHTML = "R";
            //inputGroup.appendChild(inputGroupAddon);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Amount";
            field.id = "amount";
            field.name = "amount";
            field.type = "text";

            // Status
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            label = document.createElement("label");
            formGroup.appendChild(label);

            field = document.createElement("input");
            field.type = "checkbox";
            field.id = "approved";
            label.appendChild(field);
            label.innerHTML = label.innerHTML + " Approve";

            // Index
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("input");
            formGroup.appendChild(field);

            field.className = "form-control";
            field.placeholder = "Index";
            field.id = "index";
            field.name = "index";
            field.type = "text";

            // Button
            formGroup = document.createElement("div");
            formGroup.className = "form-group";
            panelBody.appendChild(formGroup);

            field = document.createElement("button");
            formGroup.appendChild(field);

            field.className = "btn btn-default";
            field.innerHTML = "Add Transaction";
            field.type = "submit";

            field.onclick = function() {
                entry = {
                    date: document.getElementById("date").value,
                    type: document.getElementById("typeTransaction").isChecked ? "Transaction" : "Transfer",
                    category: document.getElementById("category").value,
                    description: document.getElementById("description").value,
                    amount: parseFloat(document.getElementById("amount").value),
                    approved: document.getElementById("approved").checked
                };

                index = 0;
                if (document.getElementById("index").value !== "") {
                    index = log.length - parseInt(document.getElementById("index").value);

                    if (index > log.length - 1) {
                        index = 0;
                    }
                    if (index < 0) {
                        index = 0;
                    }
                }

                log.splice(index, 0, entry);
                saveLog(log);
                renderLog(log);
            }

            // Transaction List
            panel = document.createElement("div");
            panel.className = "panel panel-default";
            panel.style.margin = "40px";
            document.body.appendChild(panel);

            panelHeading = document.createElement("div");
            panelHeading.className = "panel-heading";
            panel.appendChild(panelHeading);

            panelTitle = document.createElement("h3");
            panelTitle.className = "panel-title";
            panelTitle.innerHTML = "Transaction List";
            panelHeading.appendChild(panelTitle);

            panelBody = document.createElement("div");
            panelBody.className = "panel-body";
            panel.appendChild(panelBody);
            
            table = document.createElement("table");
            table.className = "table table-striped table-bordered";
            panelBody.appendChild(table);
            
            thead = document.createElement("thead");
            table.appendChild(thead);
            
            tr = document.createElement("tr");
            thead.appendChild(tr);
            
            th = document.createElement("th");
            th.innerHTML = "#";
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
            th = document.createElement("th");
            th.innerHTML = "Actions";
            th.setAttribute("colspan", "2");
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

            firebase.database().ref(user).set({
                list: "[" + list.join() + "]"
            });
        }

        function retrieveAndRenderLog(user) {
            firebase.database().ref(user).once("value").then(function(snapshot) {
                renderLog(JSON.parse(snapshot.child("list").val()));
            });
        }

        // retrieveAndRenderLog("rnsnkl@gmail.com".split("@").join("_at_").split(".").join("_dot_"));
    </script>
</html>
