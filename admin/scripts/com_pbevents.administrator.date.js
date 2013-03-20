// function string format
String.prototype.format = function() {
    var formatted = this;
    for (var i = 0; i < arguments.length; i++) {
        var regexp = new RegExp('\\{'+i+'\\}', 'gi');
        formatted = formatted.replace(regexp, arguments[i]);
    }
    return formatted;
};

function DateField(params) {
	this.params = params;
	var self = this;
	this.count = 0;
	
	this.rootID = "date-complete-option";
	this.checkbox = "henable";
	self.extra = {} // extra date
	this.pk = null;
	
	this.setupPicker = function(date, hour) {
		if (date) {
			new Picker.Date(date, {
				positionOffset: {x: 0, y: 0},
				pickerClass: 'datepicker_jqui',
				useFadeInOut: !Browser.ie,
				format: self.params.dateformat
			});
		}
		if (hour) {
			new Picker.Date(hour, {
				timePickerOnly: "time",
				positionOffset: {x: 0, y: 0},
				pickerClass: 'datepicker_jqui',
				useFadeInOut: !Browser.ie,
				format: self.params.hourformat
			});
		}
	}
	this.makeHtml = function() {
		html = new Array(
			'<div class="{0}" id="{1}" >'.format(this.rootID, this.rootID),
				'<div class="date-controls">',
					'<div class="control-group">',
						'<div class="control-label"><label>{0}</label></div>'.format(this.params.date.label),
						'<div class="controls"><input type="text" class="date" name="date" value=""/></div>',
					'</div>',
					'<div class="control-group">',
						'<div class="control-label btn-henable-control">',
							// formatando o estado de apresentacao do checkbox
							'<input type="checkbox" {0} class="{1}" name="{2}" pk="{3}" value="{4}"/>'.format(
									this.extra.check ? 'checked':'', this.checkbox, this.checkbox, 
									self.pk, this.extra.check ? 1:0),
							'<span class="tooltip-check">{0}</span>'.format(this.params.henable.label),
						 '</div>',
					 '</div>',
					'<div class="control-group">',
						'<div class="control-label"><label>{0}</label></div>'.format(this.params.hstart.label),
						'<div class="controls">',
							'<input type="text" {0} class="hstart" name="hstart" value=""/>'.format(this.extra.check ? '':'disabled'),
						'</div>',
					'</div>',
					'<div class="control-group">',
						'<div class="control-label"><label>{0}</label></div>'.format(this.params.hend.label),
						'<div class="controls">',
							'<input type="text" {0} class="hend" name="hend" value=""/>'.format(this.extra.check ? '':'disabled'),
						'</div>',
					'</div>',
				'</div>',
				'<div class="control-group">',
					'<div class="control-label"><label>{0}</label></div>'.format(this.params.description.label),
					'<div class="controls"><textarea name="description" rows="2" cols="30"></textarea></div>',
				'</div>',
				'<div class="control-group">',
					'<div class="control-label btn-delete-control" pk="{0}">'.format(self.pk),
						'<img src="{0}"/><span>{1}</span>'.format(this.params.image.src, this.params.image.label),
					 '</div>',
				 '</div>',
				'<hr>',
			'</div>');
		return html.join("");
	}
	this.getRoot = function(pk) {
		// raiz do objeto de data
		return jQuery("#"+self.rootID+self.params.sep+pk);
	}
	this.onDateCheck = function(event) {
		// atencao que 'this' vem de fora.
		_this = jQuery(this);
		
		var root = self.getRoot(_this.attr("pk"));
		var hstart = root.find("input.hstart");
		var hend = root.find("input.hend");
		
		hstart.prop('disabled',!hstart.prop('disabled'));
		hend.prop('disabled',!hend.prop('disabled'));
		_this.attr("value", _this.is(':checked') ? 1:0);
	}
	this.removeObject = function(event) {
		var _this = jQuery(this);
		var root = self.getRoot(_this.attr("pk"));
		var hide = false;
		
		root.find(":input").each(function(index, element) {
			var object = jQuery(element);
			var name = object.attr("name");
			
			name = name.split(self.params.sep);
			
			var operation = name[2];
			var index = name[1];
			var name = name[0];
			
			if (operation == "update") {
				values = new Array(name, index, "delete");
				object.attr("name", values.join(self.params.sep)); 
				hide = true;
			}
		});
		if (hide) root.css("display","none"); // hide only
		else root.remove(); // remove all
	}
	this.setupName = function(index, object) {
		var object = jQuery(object);
		object.attr("name", object.attr("name")+ self.params.sep +(
				self.extra.id ? 
					self.extra.id +self.params.sep +"update": 
					self.count +self.params.sep +"create")
				);
	}
	this.setup = function(extra) {
		self.extra = extra
	}
	this.createObject = function(event) {
		if (self.extra.id == null) self.count++;
		self.pk = self.extra.id ? self.extra.id: self.count;
		
		self.params.conteiner.append(self.makeHtml());
		var object = jQuery("#"+self.rootID);
		
		// abilita a funcão do checkbox para o campo hora
		var checkboxObject = jQuery("."+self.checkbox);
		checkboxObject.unbind("click"); // clear multiple calls
		checkboxObject.click(self.onDateCheck);
		
		if (self.extra.id) {
			object.find("[name=date]").attr("value", self.extra.date);
			object.find("[name=hstart]").attr("value", self.extra.hstart);
			object.find("[name=hend]").attr("value", self.extra.hend);
			object.find("[name=description]").attr("value", self.extra.desc);
			object.attr("id", self.rootID +self.params.sep +self.pk);
		} else {
			object.attr("id", self.rootID +self.params.sep +self.pk);
		}
		object.find(":input").each(self.setupName)
		object.find("div.btn-delete-control").click(self.removeObject)
		
		self.setupPicker(object.find("[name^=date]", null));
		self.setupPicker(null, object.find("[name^=hstart]"));
		self.setupPicker(null, object.find("[name^=hend]"));
		self.extra = {} // temp only
	}
}




