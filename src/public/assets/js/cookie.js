/**
 * Function for Cookie Transaction
 * 
 * Source : https://stackoverflow.com/questions/2144386/how-to-delete-a-cookie
 * @param {string} key 
 * @param {string} value 
 * @param {int} expired_in_day 
 * 
 * Usage : 
 * lokaCCookie.set( 'name_cookie' , "{ data:cookie }", 10 ); //10 Days
 * lokaCCookie.get( 'name_cookie' ); { data:cookie }
 * lokaCCookie.clean( 'name_cookie' ); 
 */
 const lokaCCookie = {
    set: ( key, value, expired_in_day ) => {
        var expires = "";
        var date = new Date();
    
        // Set Expired
        if (expired_in_day) {
            date.setTime(date.getTime() + (expired_in_day * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        } else {
            date.setTime(date.getTime() + (0.0001 * 24 * 60 * 60 * 1000)); // Default Expired
            expires = "; expires=" + date.toUTCString();
        }
    
        document.cookie = key + "=" + (value || "") + expires + "; path=/;SameSite=Lax";
    },
    get: (key) => {
		const value = `; ${document.cookie}`;
		const parts = value.split(`; ${key}=`);
		if (parts.length === 2){
			return parts.pop().split(';').shift();
		}
		return "";
    },
    clean: ( key ) => {
        lokaCCookie.set( key, null, null );
    }
}
