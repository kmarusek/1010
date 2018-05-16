/*global define, console, window, HTMLImageElement, Promise*/

(function (root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define("AtlasPlayer", ["jquery", "Behaviors"], factory);
    } else {
        root.AtlasPlayer = factory(root.jQuery, root.Behaviors);
    }
}(this, function ($, Behaviors) {
    "use strict";
    var module = {};

    /* A Behavior that plays an image atlas on a canvas.
     *
     * Atlas description format is that which is generated by the following
     * Photoshop script: https://github.com/tonioloewald/Layer-Group-Atlas
     */
    function AtlasPlayer() {
        Behaviors.init(AtlasPlayer, this, arguments);

        this.deinitialize_stop = false;

        this.$canvas = this.$elem;
        if (!this.$canvas.is("canvas")) {
            this.$canvas = this.$elem.find("canvas");
        }

        this.context = this.$canvas[0].getContext("2d");

        this.image = undefined;
        this.atlas_data = undefined;

        //TODO: Make configurable
        this.anim_player_running = false;

        this.ready().then(function () {
            $(window).on("resize", this.size_canvas_to_fit.bind(this));
            
            this.anim_length = this.find_anim_length();
            
            if (this.atlas_data.autoplay === true) {
                this.play();
            }
        }.bind(this));
    }

    Behaviors.inherit(AtlasPlayer, Behaviors.Behavior);

    AtlasPlayer.QUERY = "[data-atlasplayer]";

    /* Cause AtlasPlayer to ensure it's image and atlas are ready.
     *
     * Returns a promise which resolves when the atlas is ready for playback.
     * Promise will reject if the image is an image tag which has failed to
     * load.
     */
    AtlasPlayer.prototype.ready = function () {
        if (this.ready_promise === undefined) {
            this.ready_promise = new Promise(function (resolve, reject) {
                this.ready_resolve = resolve;
                this.ready_reject = reject;
            }.bind(this));

            this.find_image();
            this.find_atlas();
            this.is_ready();
        }

        return this.ready_promise;
    };

    /* Determine if the atlas is ready for playback.
     *
     * Calling this function also has the side effect of resolving the ready
     * promise if it has not already been done. If this function returns true
     * for the first time, then the promise has been resolved. If it returns
     * false, it may have been rejected (say, if the image fails to load).
     */
    AtlasPlayer.prototype.is_ready = function () {
        var image_ready, atlas_ready, total_ready;

        if (this.image === undefined) {
            image_ready = false;
        } else if (this.image.constructor === HTMLImageElement) {
            if (this.image.complete) {
                if (this.image.naturalHeight === 0) {
                    //Something has gone horribly wrong
                    this.ready_reject();
                    image_ready = false;
                } else {
                    image_ready = true;
                }
            } else {
                image_ready = false;
            }
        } else {
            //Other drawables are presumed already loaded
            image_ready = true;
        }

        if (this.atlas_data !== undefined && this.atlas_data.then !== undefined) {
            //Not ready, since a promise was provided
            atlas_ready = false;
        } else {
            atlas_ready = this.atlas_data !== undefined;
        }

        total_ready = image_ready && atlas_ready;

        if (total_ready) {
            this.ready_resolve();
        }

        return total_ready;
    };

    /* Called to find the image we're drawing our animation from, if present.
     */
    AtlasPlayer.prototype.find_image = function () {
        var image_id = this.$elem.data("atlasplayer-image");

        if (this.image !== undefined) {
            return;
        }

        if (image_id !== undefined) {
            this.image = $(image_id)[0];

            if (this.image.constructor === HTMLImageElement) {
                $(this.image).on("load", this.is_ready.bind(this));
            }
        }
    };

    /* Called to find and load our atlas data.
     */
    AtlasPlayer.prototype.find_atlas = function () {
        var atlas_data = this.$elem.data("atlasplayer-data");

        if (this.atlas_data !== undefined) {
            return;
        }

        if (typeof atlas_data === "string") {
            //Atlas data is a URL.
            this.atlas_data = this.load_atlas_data(atlas_data)
                .then(function (data) {
                    this.atlas_data = data;
                    this.is_ready();
                }.bind(this))
                .catch(this.ready_reject);
        } else {
            //Atlas data is immediately provided.
            this.atlas_data = atlas_data;
        }
    };
    
    AtlasPlayer.prototype.find_anim_length = function () {
        var anim_length = this.$elem.data("atlasplayer-animlength");
        
        if (anim_length === undefined) {
            anim_length = this.atlas_data.time;
        }
        
        if (anim_length === undefined) {
            anim_length = "5s";
        }
        
        anim_length = parseFloat(anim_length, 10);
        
        if (isNaN(anim_length) || anim_length === 0) {
            anim_length = 5000;
        } else {
            anim_length *= 1000;
        }
        
        return anim_length;
    };

    /* Load the atlas data.
     *
     * Returns a promise which resolves when the atlas data has loaded.
     */
    AtlasPlayer.prototype.load_atlas_data = function (url) {
        var promiseResolve, promiseReject,
            myPromise = new Promise(function (resolve, reject) {
                promiseResolve = resolve;
                promiseReject = reject;
            });

        $.ajax({
            "url": url,
            "dataType": "json"
        }).done(function (data) {
            promiseResolve(data);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            promiseReject([textStatus, errorThrown]);
        });

        return myPromise;
    };

    /* Cause the canvas to draw a particular atlas frame.
     */
    AtlasPlayer.prototype.draw_frame = function (frame_id) {
        var layerData = this.atlas_data.layers[this.atlas_data.layers.length - frame_id - 1];
        
        if (layerData.width <= 0) {
            return;
        }
        
        if (layerData.height <= 0) {
            return;
        }

        this.context.drawImage(this.image,
                               //Location of the atlas slice
                               layerData.packedOrigin.x * this.image_x_space,
                               layerData.packedOrigin.y * this.image_y_space,
                               layerData.width * this.image_x_space,
                               layerData.height * this.image_y_space,
                               //Where we want it
                               layerData.left,
                               layerData.top,
                               layerData.width,
                               layerData.height
                              );
    };
    
    /* Size the canvas to fit our data.
     */
    AtlasPlayer.prototype.size_canvas_to_fit = function () {
        //Determine the device-specific pixel size of this AtlasPlayer.
        this.canvas_scale_factor = window.devicePixelRatio;
        this.$canvas[0].width = this.$canvas.width() * this.canvas_scale_factor;
        this.$canvas[0].height = this.$canvas.height() * this.canvas_scale_factor;
        
        //Reset the current canvas transform, if any.
        this.context.setTransform(1, 0, 0, 1, 0, 0);
        
        //Scale down our coordinate space
        this.context.scale(this.canvas_scale_factor, this.canvas_scale_factor);
        
        //Determine if cropping is needed.
        this.css_aspect_ratio = this.$canvas.width() / this.$canvas.height();
        this.atlas_aspect_ratio = this.atlas_data.width / this.atlas_data.height;
        if (this.css_aspect_ratio > this.atlas_aspect_ratio) {
            this.context.translate(0, (this.$canvas.width() / this.atlas_aspect_ratio - this.$canvas.height()) / -2);
            
            this.canvas_transform_scale_factor = this.$canvas.width() / this.atlas_aspect_ratio / this.atlas_data.height;
            this.context.scale(this.canvas_transform_scale_factor, this.canvas_transform_scale_factor);
        } else if (this.css_aspect_ratio < this.atlas_aspect_ratio) {
            this.context.translate((this.$canvas.height() * this.atlas_aspect_ratio - this.$canvas.width()) / -2, 0);
            
            this.canvas_transform_scale_factor = this.$canvas.height() * this.atlas_aspect_ratio / this.atlas_data.width;
            this.context.scale(this.canvas_transform_scale_factor, this.canvas_transform_scale_factor);
        }
        
        //We also need to determine if our atlas image is scaled down and adjust
        //our source coordinate space to match.
        this.image_x_space = this.image.width / this.atlas_data.atlas.width;
        this.image_y_space = this.image.height / this.atlas_data.atlas.height;
        
        //Since we just clared the canvas, if we aren't animated, then we need
        //to manually repopulate ourselves:
        if (this.last_frame_drawn !== undefined) {
            this.draw_frame(this.last_frame_drawn);
        }
    };

    AtlasPlayer.prototype.animation_krnl = function (time) {
        var step, frame, total_frames;

        if (this.deinitialize_stop) {
            return;
        }
        
        if (this.playing === false) {
            this.anim_player_running = false;
            
            if (this.on_animation_complete) {
                this.on_animation_complete();
                this.on_animation_complete = undefined;
            }
            return;
        }

        if (this.anim_first_time === undefined) {
            this.anim_first_time = time;
            window.requestAnimationFrame(this.animation_krnl.bind(this));
            return;
        }

        total_frames = this.atlas_data.layers.length;
        step = this.anim_length / total_frames;
        time = time - this.anim_first_time;
        frame = Math.max(Math.min(Math.round(time / step), total_frames - 1), 0);

        if (this.reverse) {
            frame = (total_frames - 1) - frame;
        }

        this.context.clearRect(0,0,this.atlas_data.width, this.atlas_data.height);
        this.draw_frame(frame);
        
        this.last_frame_drawn = frame;
        
        if (time > this.anim_length) {
            if (this.atlas_data.loop === true) {
                this.anim_first_time = undefined;
            } else {
                this.anim_player_running = false;
                
                if (this.on_animation_complete) {
                    this.on_animation_complete();
                    this.on_animation_complete = undefined;
                }
                return;
            }
        }
        
        window.requestAnimationFrame(this.animation_krnl.bind(this));
    };

    AtlasPlayer.prototype.update_animation_state = function () {
        if (this.playing && this.anim_player_running === false) {
            if (this.on_animation_complete) {
                this.on_animation_complete();
            }
            
            this.animation_promise = new Promise(function (resolve, reject) {
                this.on_animation_complete = resolve;
            }.bind(this));
            
            this.size_canvas_to_fit();
            this.anim_player_running = true;
            window.requestAnimationFrame(this.animation_krnl.bind(this));
        }
        
        return this.animation_promise;
    };
    
    AtlasPlayer.prototype.play = function () {
        this.playing = true;
        this.reverse = false;
        this.anim_first_time = undefined;
        return this.update_animation_state();
    };

    AtlasPlayer.prototype.play_reverse = function () {
        this.playing = true;
        this.reverse = true;
        this.anim_first_time = undefined;
        return this.update_animation_state();
    };
    
    /* Request the animation to stop playing on the next frame.
     * 
     * This function also resets the animation to play again.
     */
    AtlasPlayer.prototype.stop = function () {
        //A bit of subtlety: We don't clear anim_player_running since we don't
        //cancel the animation frame when you stop the animation. We instead
        //wait for the animation to stop itself.
        this.playing = false;
        this.reverse = false;
        this.anim_first_time = undefined;
        return this.update_animation_state();
    };
    
    AtlasPlayer.prototype.seek = function (frame) {
        if (frame < 0) {
            frame = this.atlas_data.layers.length - frame - 2;
        }

        this.context.clearRect(0,0,this.atlas_data.width, this.atlas_data.height);
        this.draw_frame(frame);

        this.last_frame_drawn = frame;
    };

    Behaviors.register_behavior(AtlasPlayer);

    module.AtlasPlayer = AtlasPlayer;

    return module;
}));
