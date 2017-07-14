<!DOCTYPE html>
<html ng-app="createCriteria">

    <head>
        <title>Add Criteria</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/criteria.css">
        <script src='../assets/js/angular.min.js'></script>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/criteria.js"></script>
    </head>

    <body ng-controller="criteriaCtrl" ng-init="init()">

        <div class="col-md-4">

            <h1 class="text-center">ADD CRITERIA</h1>
            <br/>
            
            <form name="criteriaForm" ng-submit="addCriteria">

                <div class="criteria_div">
                    <label for="criteria_name">Criteria Name</label><br/>
                    <input type="text" name="criteria_name" ng-model="new_name" id="criteria_name" required/>
                </div>

                <div class="criteria_div">
                    <label for="criteria_weight">Criteria Weight</label><br/>
                    <input type="number" name="criteria_weight" ng-model="new_weight" id="criteria_weight" required/><br/>
                </div>

                <div class="criteria_div">
                    <label for="criteria_desc">Criteria Description</label></br/>
                    <textarea type="text" name="criteria_desc" ng-model="new_desc" id="criteria_desc" required/></textarea><br/>
                </div>

                <div class="criteria_div text-right">
                    <input rows="4" ng-disabled="criteriaForm.$invalid" ng-click="addCriteria()" class="btn btn-primary btn-submit" type="submit" value="ADD">
                </div>
            </form>

        </div>
            
        <div class="col-md-6" id="criteria-output">

            <h1 class="text-center">EVENT CRITERIA</h1>
            <br/>

            <h4 ng-show="!criterias.length" class="text-center"><i>~ No criterias ~</i></h4>

            <ul>
                <li ng-repeat="criteria in criterias" class="{'fadeOut' : criteria.done}">
                    <div class="container-fluid" id="criteria-element-{{}}">
                        <span ng-click="deleteCriteria($index)" class="btn btn-danger pull-right">&times;</span>
                        <p>NAME: {{criteria.criteria_desc}}</span>
                        <p>WEIGHT: {{criteria.criteria_weight}}</span>
                        <p>DESCRIPTION: {{criteria.criteria_desc}}</p> 
                    </div>
                </li>
            </ul>

            <br/>
            <br/>
            <p class="text-center"><button ng-class="{'disabled': criterias.length==0}" class="btn btn-primary" ng-click="submitCriteria()">SUBMIT</button>
            </p>

        </div>

    </body>

</html>
