var locations = function(){
    var _Singleton = function(){
        var obj = this;
		
		obj.json = {};
		
		obj.set = function(key, value){
			obj.json[key] = value;
			obj.setLocationJSON();
			return this;
		};
		
		obj.get = function(key){
			return obj.getLocationJSON()[key];
		};
		
		obj.getLocationJSON = function(){
			obj.json = $.deparam(location.hash.replace('#', ''));
			return obj.json;
		};
		
		obj.setLocationJSON = function(){
			location.hash = $.param(obj.json);
			return this;
		};
		
        return obj;
    },
    _inst = null;
 
    return {
        getInstance:function(){
           if(!_inst ){
               // создание уникального экземпляра класса
               _inst  = new _Singleton();
           }
           return _inst;
        }
    };
}();