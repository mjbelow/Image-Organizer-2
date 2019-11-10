<!DOCTYPE html>
<html>
<head>
<title>Main Page</title>
<meta charset="UTF-8">
<style type="text/css">
.output {
  width: 100%;
  display: none;
}

html, body {
  height: 100%;
}
</style>
<script type="application/javascript" src="js/jquery.min.js"></script>
<script type="application/javascript">
// source: https://www.geeksforgeeks.org/sets-in-javascript/
// Performs difference operation between 
// called set and otherSet 
Set.prototype.difference = function(otherSet) 
{ 
    // creating new set to store difference 
     var differenceSet = new Set(); 
  
    // iterate over the values 
    for(var elem of this) 
    { 
        // if the value[i] is not present  
        // in otherSet add to the differenceSet 
        if(!otherSet.has(elem)) 
            differenceSet.add(elem); 
    } 
  
    // returns values of differenceSet 
    return differenceSet; 
}

<?php
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a05";
$password="!c2375aU!";
$dbname="c2375a05test";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

// index to store values needed for interactive menu
//$index = array();

// sql query to build index
$query = "select category, choice, count from my_index";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($category, $choice, $count);
    while ($stmt->fetch()) {

      // hacky solution . " " so because we need a string for this value so it can convert to BigInt easily
      // if we don't add a space, javascript automatically interprets it as a number_format
      $index[$category][$choice . " "] = $count;

    }
    $stmt->close();
}


// sql query to build menu
$query = "select id, category, choice from my_options";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($id, $category, $choice);
    while ($stmt->fetch()) {
      
      $categories[$id]=$category;
      
      if(!isset($choices[$id])) {
        
        $choices[$id] = array();
        
      }

      if($choice)
        array_push($choices[$id], $choice);
      
    }
    $stmt->close();
}




echo "var index = JSON.parse('" . json_encode($index) . "');";
echo "\n";
echo "var my_categories = JSON.parse('" . json_encode($categories) . "');";
echo "\n";
echo "var my_choices = JSON.parse('" . json_encode($choices) . "');";



$con->close();


?>

var submit=true;

</script>
<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<header>

  <form action="components/upload page/image.html" target="content" method="get">
    <button id="upload">Upload</button>
  </form>
  
  <input id="login_name" autocomplete="off">
  <input id="login_pass">
  
  username
  <a href="#">Log out</a>

</header>


<script>
function submit_options(e)
{
  e.preventDefault();
  
  // prevent form from being submitted until it has finished processing
  if(!submit)
    return;
  submit=false;

  $.post("modify options.php",
    {
      option: option.value,
      action: action.value,
      category: category_mod.value,
      choice: choice_mod.value,
      position: position.value,
      name: option_name.value,
      category_id: category_id.value,
      choice_id: choice_id.value
    },
    function(data)
    {
      
      var user_data = JSON.parse(data);
      index=user_data[0];
      my_categories=user_data[1];
      my_choices=user_data[2];
      
      update_category_menu();
      modify_options(true);
      document.getElementById('options').removeChild(document.getElementById('options_menu'));
      build_menu();
      add_choice_functionality();
      add_menu_fucntionality();
      initiate();
      submit=true;
    }
  );

}
</script>

<main>
  <section id="menu">
  
    <!--<form id="modify" method="get" onsubmit="submit_options(event)" action="modify options.php" target="content" method="post">-->
    <!--<form id="modify" action="modify options.php" target="content" method="post">-->
    <form id="modify" onsubmit="submit_options(event)">
     <!--action="components/image page/index.php" target="content" method="get" onreset="initiate()-->
      <fieldset>
        <legend>Modify Options</legend>

        Option
        <select id="option" onchange="modify_options(true)" name="option">
          <option>category</option>
          <option>choice</option>
        </select>

        <br>

        Action
        <select id="action" onchange="modify_options(true)" name="action">
          <option>create</option>
          <option>update</option>
          <option>delete</option>
        </select>

        <hr>

        Category
        <select id="category" onchange="modify_options(true)" name="category"></select>

        <br>

        Choice
        <select id="choice" onchange="modify_options(false)" name="choice"></select>

        <hr>

        Position
        <select id="position" name="position"></select>

        <br>

        Name
        <input id="name" name="name">

        <hr>
        
        <div class="output">
          <input id="category_id" name="category_id">
          <input id="choice_id" name="choice_id">
        </div>
        
        <input type="submit" value="Modify Options">
      </fieldset>
    </form>
    
    <form action="components/image page/index.php" target="content" method="get" onreset="initiate()">
      
      <div id="form_options">
        <input type="submit" value="Show Images">
        <input type="reset" value="Reset">

        <br>
        <br>
        <fieldset>
          <legend>Include</legend>
          <input type="radio" name="in_operator" class="operator in" checked>
          OR
          <input type="radio" name="in_operator" class="operator in">
          AND
          <input type="radio" name="in_operator" class="operator in">
          XOR
        </fieldset>
        
        <br>
        
        <fieldset>
          <legend>Exclude</legend>
          <input type="radio" name="ex_operator" class="operator ex" checked>
          OR
          <input type="radio" name="ex_operator" class="operator ex">
          AND
          <input type="radio" name="ex_operator" class="operator ex">
          XOR
        </fieldset>
        
      </div>
      
      <div id="options"></div>
    
      <script type="application/javascript">
      function build_menu()
      {
        // category list
        var category_list = document.createElement("ul");
        category_list.id="options_menu";

        //var category = Object.keys(options);
        var category = my_categories;
        var category_count = category.length;

        for(var i = 0; i < category_count; i++)
        {
          // category item
          var category_item = document.createElement("li");
          
          // category checkbox option
          var category_checkbox = document.createElement("input");
          category_checkbox.type="checkbox";
          category_checkbox.className="category";
          
          // category output (used to handle form data)
          var category_output = document.createElement("input");
          category_output.className="output";
          category_output.disabled="true";
          category_output.name="output[]";

          // category choice list
          var choice_list = document.createElement("ul");
          
          //var choice = options[category[i]];
          var choice = my_choices[i];
          var choice_count = choice.length;
          
          
          for(var j = 0; j < choice_count; j++)
          {
            // choice item
            var choice_item = document.createElement("li");
            
            // choice checkbox option (include)
            var choice_checkbox = document.createElement("input");
            choice_checkbox.type="checkbox";
            choice_checkbox.className="choice in";
            choice_checkbox.dataset.group=(i+1);
            choice_checkbox.dataset.bin=Math.pow(2, j);

            // append (include) to choice item
            choice_item.appendChild(choice_checkbox);
            
            // choice checkbox option (exclude);
            choice_checkbox = choice_checkbox.cloneNode(false);
            choice_checkbox.className="choice ex";
            
            // append (exclude) to choice item
            choice_item.appendChild(choice_checkbox);
            
            // add choice name
            choice_item.innerHTML += choice[j] + " <span></span>";
            
            // append to choice list
            choice_list.appendChild(choice_item);
          }
          
          // append to category item
          category_item.appendChild(category_output);
          
          
          category_item.appendChild(category_checkbox);
          category_checkbox = category_checkbox.cloneNode(false);
          category_item.appendChild(category_checkbox);
          
          category_item.innerHTML += category[i] + " <span></span>";
          category_item.appendChild(choice_list);
          
          // append to category list
          category_list.appendChild(category_item);
          
          // append to document
          document.getElementById("options").appendChild(category_list);
        }
      }

      build_menu();

      //////////////////
      //              //
      //  MENU LOGIC  //
      //              //
      //////////////////
      
      var or_op = true;
      var and_op = false;

      var ex_or_op = true;
      var ex_and_op = false;

      var choice;
      
      function add_choice_functionality()
      {
        choice = document.getElementsByClassName("choice");
        var count = choice.length;

        for(var i = 0; i < count; i++)
        {

          choice[i].onchange=function()
          {

            // include logic
            var group_category = this.parentElement.parentElement.parentElement.getElementsByClassName("category")[0];
            var group_choice = this.parentElement.parentElement.parentElement.getElementsByClassName("choice in");    
            
            // exclude logic
            var ex_group_category = this.parentElement.parentElement.parentElement.getElementsByClassName("category")[1];
            var ex_group_choice = this.parentElement.parentElement.parentElement.getElementsByClassName("choice ex");
            
            var group_output = this.parentElement.parentElement.parentElement.getElementsByClassName("output")[0];
            var group_count = group_choice.length;
            var group = this.dataset.group;
            
            // include logic
            var image_keys = new Set();
            var image_keys_not = new Set();
            var selection = new Set();
            
            // exclude logic
            var ex_image_keys = new Set();
            var ex_selection = new Set();
            
            var group_selected = 0;

            // include logic
            var active = 0n;
            
            // exclude logic
            var ex_active = 0n;

            var max = 0n;
            
            for(var j = 0; j < group_count; j++)
            {
            
              // include logic
              if(group_choice[j].checked)
                active |= BigInt(group_choice[j].dataset.bin);
              
              //exclude logic
              if(ex_group_choice[j].checked)
                ex_active |= BigInt(ex_group_choice[j].dataset.bin);
              
              max |= BigInt(group_choice[j].dataset.bin);
            }

            // change state of category checkbox
            // include logic
            if(active == max)
              group_category.checked=true;
            else
              group_category.checked=false;
            if(active != max && active != 0)
              group_category.indeterminate=true;
            else
              group_category.indeterminate=false;
            
            // exclude logic
            if(ex_active == max)
              ex_group_category.checked=true;
            else
              ex_group_category.checked=false;
            if(ex_active != max && ex_active != 0)
              ex_group_category.indeterminate=true;
            else
              ex_group_category.indeterminate=false;

            // if no images exist for the category, don't go any further
            if(!index[group])
              return;

            ////////////////////////////////
            //                            //
            //  UPDATE MENU INDEX VALUES  //
            //                            //
            ////////////////////////////////
            
            var group_keys = Object.keys(index[group]);
            var group_keys_count = group_keys.length;

            var group_total = 0;

            for(var j = 0; j < group_keys_count; j++)
            {

              group_total += index[group][group_keys[j]];

            }
            
            // update value for all choices
            for(var j = 0; j < group_count; j++)
            {
              // include logic
              var selected = 0;
              
              // exclude logic
              var ex_selected = 0;
              
              var total = 0;
              var bin = BigInt(group_choice[j].dataset.bin);
              
              // include logic
              var n;
              if(or_op | and_op)
                n = active | bin;
              else
                n = active & ~bin;
              
              // exclude logic
              var ex_n;
              if(ex_or_op | ex_and_op)
                ex_n = ex_active | bin;
              else
                ex_n = ex_active & ~bin;
              
              // include logic
              var choice_checked = group_choice[j].checked;
              
              // exclude logic
              var ex_choice_checked = ex_group_choice[j].checked;
              
              for(var k = 0; k < group_keys_count; k++)
              {
                
                group_keys[k] = BigInt(group_keys[k]);
                
                // include logic
                var bool;
                if(and_op)
                  bool = ((n & group_keys[k]) == n);
                else if(or_op)
                  bool = ((bin & group_keys[k]) == bin);
                else
                  bool = (((n & group_keys[k]) == 0) && ((bin & group_keys[k]) == bin));
                
                // exclude logic
                var ex_bool;
                if(ex_and_op)
                  ex_bool = ((ex_n & group_keys[k]) == ex_n);
                else if(ex_or_op)
                  ex_bool = ((bin & group_keys[k]) == bin);
                else
                  ex_bool = (((ex_n & group_keys[k]) == 0) && ((bin & group_keys[k]) == bin));
                
                if((bin & group_keys[k]) == bin)
                  total += index[group][group_keys[k]+" "];
                
                // include logic
                if(bool)
                {
                  //console.log("item " + j + ":\t" + group_keys[k])
                  if(choice_checked)
                  {
                    image_keys.add(k);
                    selection.add(group_keys[k]);
                  }
                  else
                  {
                    image_keys_not.add(k);
                  }
                  selected += index[group][group_keys[k]];
                }
                
                // exclude logic
                if(ex_bool)
                {
                  if(ex_choice_checked)
                  {
                    ex_image_keys.add(k);
                    ex_selection.add(group_keys[k]);
                  }
                  ex_selected += index[group][group_keys[k]];
                  //selected -= index[group][group_keys[k]];
                }
              
              }
              
              var info = group_choice[j].parentElement.getElementsByTagName("span")[0];
              //info.innerHTML = selected + " / " + total + ") (" + ex_selected + " / " + total;

              //if(ex_active)
              //  selected -= ex_selected;
              //selected = selected < 0 ? 0 : selected;

              info.innerHTML = selected + " / " + total;
              group_choice[j].dataset.total = total;
            }
            
            image_keys = image_keys.difference(ex_image_keys);
            image_keys_not = image_keys_not.difference(ex_image_keys);
            
            var image_keys_values = image_keys.values();
            var image_keys_count = image_keys.size;
            var image_keys_not_count = image_keys_not.size;

            var group_info = group_category.parentElement.getElementsByTagName("span")[0];
            var group_selected = 0;
            
            for(var j = 0; j < image_keys_count; j++)
            {
              var key = image_keys_values.next().value;
              
              
              group_selected += index[group][group_keys[key]+" "];
            
            }
            
            
            for(var j = 0; j < group_count; j++)
            {
              
              var image_keys_values = image_keys.values();
              var image_keys_not_values = image_keys_not.values();
              var bin = BigInt(group_choice[j].dataset.bin);
              var selected = 0;
              var choice_checked = group_choice[j].checked;
              
              if(choice_checked)
              {
                for(var k = 0; k < image_keys_count; k++)
                {
                  
                  var key = image_keys_values.next().value;
                  
                  
                  if(((bin & group_keys[key]) == bin))
                  {
                    selected += index[group][group_keys[key]+" "];
                  }
                  

                  
                }
              }
              else
              {
                for(var k = 0; k < image_keys_not_count; k++)
                {
                  
                  var key = image_keys_not_values.next().value;
                  
                  if(((bin & group_keys[key]) == bin))
                  {
                    selected += index[group][group_keys[key]+" "];
                  }
                  
                  
                }
              }
              
              
              var info = group_choice[j].parentElement.getElementsByTagName("span")[0];
              //info.innerHTML = selected + " / " + total + ") (" + ex_selected + " / " + total;

              //if(ex_active)
              //  selected -= ex_selected;
              //selected = selected < 0 ? 0 : selected;

              info.innerHTML = selected + " / " + group_choice[j].dataset.total;
              
            }
            

            group_info.innerHTML = group_selected + " / " + group_total;

            // disable output for a category if no choices are selected
            
            selection = selection.difference(ex_selection);
            
            var output = Array.from(selection);
            
            if(output.length == 0)
            {
              group_output.disabled=true;
              group_output.value = group;
            }
            else
            {
              group_output.disabled=false;
              group_output.value = group + "," + output;
            }

          }

        }
      }
      
      add_choice_functionality();
      
      function toggle_all(checked, choice)
      {

        var count = choice.length;

        for(var i = 0; i < count; i++)
        {
          choice[i].checked = checked;
        }

      }

      var category, operator, ex_operator;

      function add_menu_fucntionality()
      {

        category = document.getElementsByClassName("category");
        var count = category.length;

        for(var i = 0; i < count; i++)
        {

          // if no choices exist for category, assign a blank function
          if(!Boolean(category[i].parentElement.getElementsByClassName("choice").length))
          {
            category[i].onchange=function(){}
          }

          // include category checkbox
          else if(i % 2 == 0)
          {
            category[i].onchange=function()
            {
              toggle_all(this.checked, this.parentElement.getElementsByClassName("choice in"));
              this.parentElement.getElementsByClassName("choice")[0].onchange();
            }
          }
          // exclude category checkbox
          else
          {
            category[i].onchange=function()
            {
              toggle_all(this.checked, this.parentElement.getElementsByClassName("choice ex"));
              this.parentElement.getElementsByClassName("choice")[0].onchange();
            }
          }

        }


        // include logic
        operator = document.getElementsByClassName("operator in");
        count = operator.length;

        for(var i = 0; i < count; i++)
        {

          operator[i].onchange=function()
          {
            or_op = operator[0].checked;
            and_op = operator[1].checked;
            var category_count = category.length;
            console.log(category[0]);
            for(var j = 0; j < category_count; j++)
            {
              category[j].parentElement.getElementsByClassName("choice")[0].onchange();
            }

          }

        }

        // exclude logic
        ex_operator = document.getElementsByClassName("operator ex");
        count = ex_operator.length;

        for(var i = 0; i < count; i++)
        {

          ex_operator[i].onchange=function()
          {
            ex_or_op = ex_operator[0].checked;
            ex_and_op = ex_operator[1].checked;
            
            var category_count = category.length;
            for(var j = 0; j < category_count; j++)
            {
              category[j].parentElement.getElementsByClassName("choice")[0].onchange();
            }
            
          }

        }
        
      }
      
      add_menu_fucntionality();
      
      // initiate menu function (also used on form reset)
      function initiate()
      {
        
        var category = document.getElementsByClassName("category");
        var count = category.length;
        
        for(var i = 0; i < count; i++)
        {
          category[i].checked=false;
          category[i].indeterminate=false;
          category[i].onchange();
        }
        
        or_op = true;
        ex_or_op = true;
        
        and_op = false;
        ex_and_op = false;
        
        operator[0].checked = true;
        ex_operator[0].checked = true;
        
      }
      
      // initiate menu
      initiate();


      </script>
    </form>
  </section>
  <iframe id="frame" name="content"></iframe>


</main>


<script type="application/javascript">
document.getElementById("frame").src="";



var option = document.getElementById("option");
var action = document.getElementById("action");

var category_mod = document.getElementById("category");
var choice_mod = document.getElementById("choice");
var category_id = document.getElementById("category_id");
var choice_id = document.getElementById("choice_id");

var position = document.getElementById("position");
var option_name = document.getElementById("name");

var categories = my_categories;
var categories_count = categories.length;

function update_category_menu()
{

  categories = my_categories;
  categories_count = categories.length;

  category_mod.innerHTML="";
  
  for(var i = 0; i < categories_count; i++)
  {  
    category_mod.innerHTML+="<option>" + categories[i] + "</option>";
  }
}

function modify_options(update)
{
  
  //var choices_count = options[category_mod.value].length;
  var choices_count = my_choices[category_mod.selectedIndex].length;
  
  var i;
  
  if(update)
    choice_mod.innerHTML="";
  
  position.innerHTML="";
  
  position.disabled=false;
  option_name.disabled=false;
  
  if(option.selectedIndex==0)
  {

    choice_mod.disabled=true;
    for(i = 0; i < categories_count; i++)
    {
      position.innerHTML+="<option>" + (i+1) + "</option>";
    }

  }
  else
  {

    choice_mod.disabled=false;
    for(i = 0; i < choices_count; i++)
    {
      if(update)
        choice_mod.innerHTML+="<option>" + my_choices[category_mod.selectedIndex][i] + "</option>";
      position.innerHTML+="<option>" + (i+1) + "</option>";
    }

  }

  if(action.selectedIndex==0)
  {
    position.innerHTML+="<option>" + (i+1) + "</option>";
    position.selectedIndex=i;
    if(option.selectedIndex==0)
      option_name.value="Category Name";
    else
      option_name.value="Choice Name";
    option_name.select();
  }
  else if(action.selectedIndex==2)
  {
    position.disabled=true;
    position.innerHTML="";
    option_name.disabled=true;
    option_name.value="";
  }
  else
  {
    
    if(option.selectedIndex==0)
    {
      option_name.value=category_mod.value;
      position.selectedIndex=category_mod.selectedIndex;
    }
    else
    {
      option_name.value=choice_mod.value;
      position.selectedIndex=choice_mod.selectedIndex;
    }
  }
  
  category_id.value=category_mod.selectedIndex+1;
  choice_id.value=choice_mod.selectedIndex+1;
  
}

update_category_menu();
modify_options(true);
</script>

</body>
</html>