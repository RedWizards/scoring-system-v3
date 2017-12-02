<?php require_once('../helpers/admin-security.php');?>

<!DOCTYPE html>
<html ng-app="teams">

    <head>
        <title>Order of Pitching</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/criteria.css">
        <link rel="stylesheet" href="../assets/css/admin-view.css">
        <script src='../assets/js/angular.min.js'></script>
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.min.js"></script>
        <script src="../assets/js/teams.js"></script>
        <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    </head>

    <body ng-controller="teamsInit" ng-init="init()">

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
            <h2 class="text-center">ORDER OF <strong>PITCHING</strong></h2>
            <br/>
                
            <div class="col-md-offset-3 col-md-6" id="teams-output">
                
                <h4 ng-show="!teams.length" class="text-center"><i> NO TEAMS </i></h4>

                <ul>
                    <li ng-repeat="team in teams" class="{'fadeOut' : teams.done}">
                        <div class="container-fluid criteria-box" id="teams-element-{{teams.teams_id}}">
                            <span ng-click="removeTeam($index, team.team_id)" class="btn btn-danger pull-right">&times;</span>
                            <table>
                                <tr>
                                    <td><b><i>Team Name</i></b></td>
                                    <td style="padding: 0 10px;">{{team.team_name}}</td>
                                </tr>
                                <tr>
                                    <td><b><i>Project Name</i></b></td>
                                    <td style="padding: 0 10px;">{{team.project_name}}</td>
                                </tr>
                                <tr>
                                    <td><b><i>Description</i></b></td>
                                    <td style="padding: 0 10px;">{{team.long_desc}}</td>
                                </tr>
                            </table>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <div id="footer" class="text-center">
            <small class="sub">POWERED BY</small><br/><strong>RED Wizard Events Management</strong><br/>&copy; 2017
        </div>

    </body>

</html>
