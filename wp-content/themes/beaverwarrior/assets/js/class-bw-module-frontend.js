class BWModuleFrontend { 

    /**
     * The main constructor method for the super class. This is called on init.
     *
     * @param  {object} settings An object of settings
     *
     * @return {void}
     */
     constructor( settings ){
        // Init sentry
        this._initSentry();

        // Add the settings
        this.settings = settings;
        // If we were provided an ID
        if ( settings.id ){
            // Add the element ID
            this.id = settings.id;
            // Add the element
            this.element = document.querySelector( '.fl-node-' + this.id );
            // If we can't find the element, abort
            if ( !this.element ){
                this.warn(`Error! Unable to locate element .fl-node-${this.id}`)
                return
            }
        } 
        // Default elements
        this.elements = {};
        // Bind the type of object this is 
        this.objectType = this._getObjectType();
        // Init the child function
        this.init();
    }   


    /**
     * Method to init the Sentry Object
     *
     * @return {void}
     */
     _initSentry(){
        if ( sentry_data.environment ){
            Sentry.init({ dsn: 'https://9097fbf7ed4247e7a5425f1a0654f37e@sentry.io/1460499' });
        }
    }

    /**
     * Method to log something to the Sentry obecjt
     *
     * @param  {object} message The object to log
     *
     * @return {void}         
     */
     sentryLog( message, data = {}, level = 'error' ){
        if ( Sentry !== undefined ){
            Sentry.captureMessage( message, {
                data        : data,
                level       : level,
                fingerprint : ['{{ default }}', sentry_data.environment]
            });
        }
    }

    /**
     * Method to get the name of the current object (used in debugging logs).
     *
     * @return {string} The name of the current object
     */
     _getObjectType(){
        return this.constructor.name;
    }

    /**
     * Method to log messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */
     debug( message ){
        // Get the arguments
        let arguments_object = arguments,
        // The default argument to log is just the message
        argument_to_log      = message;
        // If the length is more than one, we need to make an array
        if ( arguments_object.length > 1 ){
            // Redeclare the argument to log as an array
            argument_to_log = [];
            // Add all the arguments
            for ( let i=0; i<arguments.length; i++){
                argument_to_log.push( arguments[i] );
            }
        }
        // Log it
        console.debug(this.constructor.name + ' -', argument_to_log);
    }

    /**
     * Method to log messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */
     log( message ){
        // Get the arguments
        let arguments_object = arguments,
        // The default argument to log is just the message
        argument_to_log      = message;
        // If the length is more than one, we need to make an array
        if ( arguments_object.length > 1 ){
            // Redeclare the argument to log as an array
            argument_to_log = [];
            // Add all the arguments
            for ( let i=0; i<arguments.length; i++){
                argument_to_log.push( arguments[i] );
            }
        }
        // Log it
        console.log(this.constructor.name + ' -', argument_to_log);
    }

    /**
     * Method to log warning messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */
     warn( message ){
        // Get the arguments
        let arguments_object = arguments,
        // The default argument to log is just the message
        argument_to_log      = message;
        // If the length is more than one, we need to make an array
        if ( arguments_object.length > 1 ){
            // Redeclare the argument to log as an array
            argument_to_log = [];
            // Add all the arguments
            for ( let i=0; i<arguments.length; i++){
                argument_to_log.push( arguments[i] );
            }
        }
        // Log it
        console.warn(this.constructor.name + ' -', argument_to_log);
    }

    /**
     * Method to log error messages to the console.   
     *
     * @param  {mixed} message The mixed type to log
     *
     * @return {void}
     */
     error( message ){
        // Get the arguments
        let arguments_object = arguments,
        // The default argument to log is just the message
        argument_to_log      = message;
        // If the length is more than one, we need to make an array
        if ( arguments_object.length > 1 ){
            // Redeclare the argument to log as an array
            argument_to_log = [];
            // Add all the arguments
            for ( let i=0; i<arguments.length; i++){
                argument_to_log.push( arguments[i] );
            }
        }
        this.sentryLog(argument_to_log)
        // Log it
        console.error(this.constructor.name + ' -', argument_to_log);
    }
}