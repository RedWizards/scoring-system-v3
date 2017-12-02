<?php require_once('../helpers/admin-security.php');?>

<!DOCTYPE html>
<html ng-app="createCriteria">

    <head>
        <title>Add Criteria</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/criteria.css">
        <link rel="stylesheet" href="../assets/css/admin-view.css">
        <script src='../assets/js/angular.min.js'></script>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/criteria.js"></script>
        <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    </head>

    <body ng-controller="criteriaCtrl" ng-init="init()">

        <header>
            <div class="text-center">
                <h3 id="scoresheet-name"><b>ADMINISTRATOR</b> VIEW</h3>
            </div>
        </header>

        <div class="row outer-row">
        
            <span class="pull-left">
                <a href="index.php"><button id="back"><span class="glyphicon glyphicon-chevron-left"></span> BACK</button></a>
            </span>
            <br/><br/>
            <h2 class="text-center">CREATE <strong>CRITERIA</strong></h2>
            <br/>

            <div class="col-md-offset-1 col-md-5">

                <h3 class="text-center">ADD CRITERIA</h3>
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
                        <textarea type="text" name="criteria_desc" ng-model="new_desc" id="criteria_desc"/></textarea><br/>
                    </div>

                    <div class="criteria_div text-left">
                        <input rows="4" ng-disabled="criteriaForm.$invalid" ng-click="addCriteria()" class="btn btn-primary btn-submit" id="submit-criteria" type="submit" value="ADD">
                    </div>
                </form>

            </div>
                
            <div class="col-md-5" id="criteria-output">

                <h3 class="text-center">EVENT CRITERIA</h3>

                <h4 ng-show="!criterias.length" class="text-center"><i> NO CRITERIAS </i></h4>

                <ul>
                    <li ng-repeat="criteria in criterias" class="{'fadeOut' : criteria.done}">
                        <div class="container-fluid criteria-box" id="criteria-element-{{criteria.criteria_id}}">
                            <span ng-click="deleteCriteria($index, criteria.criteria_id)" class="btn btn-danger pull-right">&times;</span>
                            <table>
                                <tr>
                                    <td><b><i>Name</i></b></td>
                                    <td style="padding: 0 10px;">{{criteria.criteria_desc}}</td>
                                </tr>
                                <tr>
                                    <td><b><i>Weight</i></b></td>
                                    <td style="padding: 0 10px;">{{criteria.criteria_weight}}</td>
                                </tr>
                                <tr>
                                    <td><b><i>Description</i></b></td>
                                    <td style="padding: 0 10px;">{{criteria.criteria_longdesc}}</td>
                                </tr>
                            </table>
                        </div>
                    </li>
                </ul>

                <p class="text-center"><button ng-class="{'disabled': criterias.length==0}" class="btn btn-primary" ng-click="submitCriteria()" id="submit-criteria">SUBMIT</button>
                </p>

            </div>

        </div>

        <div id="footer" class="text-center">
            <small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
        </div>

    </body>

</html>
