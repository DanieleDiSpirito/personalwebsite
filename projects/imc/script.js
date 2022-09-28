(function() {
    var magicFocus,
        __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

    magicFocus = (function() {
        function magicFocus(_at_parent) {
            var input, _i, _len, _ref;
            this.parent = _at_parent;
            this.hide = __bind(this.hide, this);
            this.show = __bind(this.show, this);
            if (!this.parent) {
                return;
            }
            this.focus = document.createElement('div');
            this.focus.classList.add('magic-focus');
            this.parent.classList.add('has-magic-focus');
            this.parent.appendChild(this.focus);
            _ref = this.parent.querySelectorAll('input, textarea, select');
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                input = _ref[_i];
                input.addEventListener('focus', function() {
                    return window.magicFocus.show();
                });
                input.addEventListener('blur', function() {
                    return window.magicFocus.hide();
                });
            }
        }

        magicFocus.prototype.show = function() {
            var el, _base, _base1;
            if (!(typeof (_base = ['INPUT', 'SELECT', 'TEXTAREA']).includes === "function" ? _base.includes((el = document.activeElement).nodeName) : void 0)) {
                return;
            }
            clearTimeout(this.reset);
            if (typeof (_base1 = ['checkbox', 'radio']).includes === "function" ? _base1.includes(el.type) : void 0) {
                el = document.querySelector("[for=" + el.id + "]");
            }
            this.focus.style.top = (el.offsetTop || 0) + "px";
            this.focus.style.left = (el.offsetLeft || 0) + "px";
            this.focus.style.width = (el.offsetWidth || 0) + "px";
            return this.focus.style.height = (el.offsetHeight || 0) + "px";
        };

        magicFocus.prototype.hide = function() {
            var el, _base;
            if (!(typeof (_base = ['INPUT', 'SELECT', 'TEXTAREA', 'LABEL']).includes === "function" ? _base.includes((el = document.activeElement).nodeName) : void 0)) {
                this.focus.style.width = 0;
            }
            return this.reset = setTimeout(function() {
                return window.magicFocus.focus.removeAttribute('style');
            }, 200);
        };

        return magicFocus;

    })();

    window.magicFocus = new magicFocus(document.querySelector('.form'));

    $(function() {
        return $('.select').customSelect();
    });

}).call(this);