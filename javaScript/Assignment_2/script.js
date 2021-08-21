
function startProgram (){
    //Create a simple page with a script that does the following:
    // 1.	Ask the user for his/her name using prompt function, The user must provide a name (no empty strings)

    let userName = prompt ( "please enter your username ? ");
    //2.	If the user did not type a name, the script should repeat asking for his/her name until a valid string is entered
    while (userName=="" ||  userName.match(/^ *$/)){
        userName = prompt ( "please enter a valid user name ? ");
        
    }

    //3.	Ask for a password (123) to continue using prompt function, if the user type the passwordm.,.mol 3 times incorrectly an alert should appear that  
    
    const STORED_PWD ="123";
    let userPassword = prompt ( "Please enter your password ");
    isNotCorrectPassword = (STORED_PWD , userPassword) => {
        return userPassword != STORED_PWD ;
    }
    let counter = 2 ;
    while ( isNotCorrectPassword (STORED_PWD , userPassword) && counter > 0) {
        userPassword = prompt ( "Please enter your password you have "+counter+ " trial(s) left");
        counter --;
    }

    if(isNotCorrectPassword (STORED_PWD , userPassword)) {
        alert ("you’ve entered wrong password 3 times");
    } else {
        //4.	If the user entered (123) then the script should continue
        var month = getMonth();
        var day = getDay(month);
        //7.	Display an alert for the user with the correct horoscope
        var horoscope = getHoroscope ( month , day) ; 
        alert (" your horoscope is " +horoscope) ;
        // display on the page 
        var node = document.createElement("LI");                 
        var textnode = document.createTextNode(userName+" birthday is "+day+"/"+month+" and his/her horoscope is "+ horoscope);         // Create a text node
        node.appendChild(textnode);                              
        document.getElementById("userlist").appendChild(node);     
    }
}

function getHoroscope ( month , day) {
    var horoscopes = ["Aqurius" ,"Pisces","Aries" ,"Taurus" , "Gemini", "Cancer" ,"Leo" ,"Virgo" ,"Libra","Scorpio","Sagittarius","capricorn" ];
    var userHoroscope ;
    var month = Number(month);
    console.log(month); 
    switch(month) {
        case 1:
            userHoroscope=  ((day > 21) ? horoscopes[0] :  horoscopes[11]  );
            break;
        case 2:
            userHoroscope=  ((day > 20) ? horoscopes[1] :  horoscopes[0]  );
            break ;
        case 3:
            userHoroscope=  ((day > 21) ? horoscopes[2] :  horoscopes[1]  );
            break ;
        case 4:
            userHoroscope=  ((day > 21) ? horoscopes[3] :  horoscopes[2]  );
            break ;
        case 5:
            userHoroscope=  ((day > 22) ? horoscopes[4] :  horoscopes[3]  );
            break ;
        case 6:
            userHoroscope=  ((day > 22) ? horoscopes[5] :  horoscopes[4]  );
            break ;
        case 7:
            userHoroscope=  ((day > 23) ? horoscopes[6] :  horoscopes[5]  );
            break ;
        case 8:
            userHoroscope=  ((day > 23) ? horoscopes[7] :  horoscopes[6]  );
            break ;
        case 9:
            userHoroscope=  ((day > 23) ? horoscopes[8] :  horoscopes[7]  );
            break ;
        case 10:
            userHoroscope=  ((day > 22) ? horoscopes[9] :  horoscopes[8]  );
            break ;
        case 11:
            userHoroscope=  ((day > 22) ? horoscopes[10] :  horoscopes[9]  );
            break ;
        case 12:
            userHoroscope=  ((day > 22) ? horoscopes[11] :  horoscopes[10]  );
            break ;
        default:
            userHoroscope = "Error"

    }

    return userHoroscope;
}
//5.	Ask for the birth month for user, check the month is a number and a correct month; if the user entered an incorrect month repeat till a valid month is entered
function getMonth() {

    var birthMonth = prompt (" Please enter your birth month") ;
    while ( isNotValidMonth(birthMonth) ) {
        var birthMonth = prompt (" Please enter a vaild month !") ; 
    }
    return birthMonth ;

}

 //6.	Ask for the birth day (day only e.g. 30), check the day is a number and a correct day; if the user entered an incorrect day repeat till a valid day is entered
function getDay(birthMonth) {

    var birthDay = prompt (" Please enter your birth Day") ;
    while ( isNotValidDay(birthDay , birthMonth) ) {
        var birthDay = prompt (" Please enter a valid Day !") ; 
    }

    return birthDay ;

}

function  isNotValidMonth (month){
   var  month = Number (month);
    if (isNaN(month ) || ( month > 12 || month < 0)){
        return true;
    }else {
        return false;
    }
    
}

function  isNotValidDay (day , month){
   var day = Number (day);
   var month= Number (month);
   //The months having 30 days in a year are April, June, September, and November.
   var thirtyDayMonths = [4,6,9,11];
    if ( isNaN(day ) || (day > 31 || day < 0) ){
        return true;
    }else {
        if( month === 2 && day > 28) return true ;
        if ( thirtyDayMonths.includes (month) && day > 30 ) return true;
        return false;
    }
    
}
