var language = window.navigator.language;
        if (language.length > 2) {
            language = language.split('-');
            language = language[0];
        }

        //language = "fr"; // manually set language

        if (language === "en") {
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        } else if (language === "cz") {
            var weekday = ["Neděle", "Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota"];
            var month = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];
        } else if (language === "it") {
            var weekday = ['Domenica', 'Luned&#236', 'Marted&#236', 'Mercoled&#236', 'Gioved&#236', 'Venerd&#236', 'Sabato'];
            var month = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
        } else if (language === "sp") {
            var weekday = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
            var month = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        } else if (language === "de") {
            var weekday = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
            var month = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Ju li", "August", "September", "Oktober", "November", "Dez ember"];
        } else if (language === "fr") {
            var weekday = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
            var month = ["Janvie", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

        } else if (language === "zh") {
            var weekday = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
            var month = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
        } else {
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        }
        myDate = new Date();
        mnth = myDate.getMonth();
        dat = myDate.getDate();
        day = myDate.getDay();
        oday = (dat < 10 ? "0" : "") + dat;
        year = myDate.getFullYear();
        
        $('#njour').text(weekday[day]);
        $('#numj').text(oday);
        $('#ndate').attr('data-month',month[mnth]);
        $('#ndate').attr('data-year',year);
        setInterval(function(){
        myDate = new Date();
		
		
		      var dt_now = new Date();
         var hh	= dt_now.getHours();
         var mm	= dt_now.getMinutes();
         var ss	= dt_now.getSeconds();

         if(hh < 10){
             hh = "0" + hh;
         }
         if(mm < 10){
             mm = "0" + mm;
         }
         if(ss < 10){
             ss = "0" + ss;
         }
      //   $(".time").html( hh + ":" + mm );
		 
        //+myDate.getDate()
        $('.time').html(hh + ":" + mm + "<b>" + ss +"</b>");
        }, 1000);