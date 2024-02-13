<script>
    function SimpleSelect(config) {
        return {
            open: false,
            search: '',
            dataSource: config.dataSource,
            options: {},
            valueField: config.valueField,
            textField: config.textField,
            value: config.value,
            placeholder: config.placeholder,
            selected: config.selected,
            searchable: config.searchable,
            clearable: config.clearable,
            required: config.required,
            disabled: config.disabled,
            multiple: config.multiple,
            maxSelection: config.maxSelection,
            name: config.name,
            id: config.id,
            searchInputPlaceholder: config.searchInputPlaceholder,
            noOptions: config.noOptions,
            noResult: config.noResult,
            onSelect: config.onSelect,
            isLoading: false,
            popperInstance: null,
            popperHeight: '0px',

            init: function() {
                if (this.value && !this.selected) {
                    this.selected = this.value;
                }

                if (!this.selected) {
                    if (this.multiple) {
                        this.selected = [];
                    } else {
                        this.selected = null;
                    }
                }
                this.resetOptions();

                this.$watch('search', ((search) => {
                    this.resetOptions();
                    this.options = Object.values(this.dataSource)
                        .filter((value, index) => this.getOptionText(value, index).toString().toLowerCase().includes(search.toLowerCase().trim()));

                    setTimeout(() => {
                        this.popper();
                        this.scrollToOption();
                    }, 100);
                }));
            },

            generateID: () => {
                return '_' + Math.random().toString(36).substr(2, 9);
            },

            resetOptions: function(dataSource = null) {
                if (!dataSource) {
                    dataSource = this.dataSource;
                }
                this.options = Object.values(dataSource);
            },

            toggleSelect: function() {
                if (!this.disabled) {
                    if (this.open) {
                        this.closeSelect();
                    } else {
                        this.open = true;

                        setTimeout(() => {
                            this.popper();
                            this.scrollToOption();
                            this.checkMaxSelectionReached();
                        }, 700);
                    }
                }
            },

            closeSelect: function() {
                this.open = false;
                this.search = '';
            },

            selectOption: function(value) {
                if (!this.disabled) {
                    // If multiple push to the array, if not, keep that value and close menu
                    if (this.multiple) {
                        // If it's not already in there
                        if (!this.selected.includes(value)) {
                            if (this.maxSelection == 0 || (this.maxSelection > 0 && this.selected.length < this.maxSelection)) {
                                this.selected.push(value)
                            }
                            let reached = this.checkMaxSelectionReached();
                            if (reached) {
                                this.closeSelect();
                            }
                        }
                    } else {
                        this.selected = value;
                        this.closeSelect();
                    }
                    if (this.onSelect) {
                        this.$dispatch(`${this.onSelect}`, {
                            id: this.id,
                            name: this.name,
                            value: this.selected
                        });
                    }
                }
            },

            deselectOption: function(index = null) {
                if (this.multiple) {
                    this.selected.splice(index, 1)
                } else {
                    this.selected = '';
                }
            },

            checkMaxSelectionReached: function() {
                if (this.multiple && this.$refs.simpleSelectOptionsList) {
                    if (this.maxSelection > 0 && this.selected.length >= this.maxSelection) {
                        this.$refs.simpleSelectOptionsList.querySelectorAll(':scope > li').forEach((el) => {
                            el.style.pointerEvents = 'none';
                            el.style.opacity = '0.4';
                        });
                        return true;
                    } else {
                        this.$refs.simpleSelectOptionsList.querySelectorAll(':scope > li').forEach((el) => {
                            el.style.pointerEvents = 'auto';
                            el.style.opacity = '1';
                        });
                    }
                }
                return false;
            },

            getOptionValue: function(option, index) {
                return typeof option === 'object' && this.options[index] ? this.options[index][this.valueField] : option;
            },

            getOptionText: function(option, index) {
                return typeof option === 'object' && this.options[index] ? this.options[index][this.textField] : option;
            },

            getIndexFromSelectedValue: function(value) {
                let valueField = this.valueField;
                return Object.values(this.dataSource).findIndex(function(x) {
                    if (typeof x === 'object') {
                        return x[valueField] == value;
                    } else {
                        return x == value;
                    }
                });
            },

            getTextFromSelectedValue: function(value) {
                let index = this.getIndexFromSelectedValue(value);
                let valueField = this.valueField;
                let foundValue = Object.values(this.dataSource).find(function(x) {
                    if (typeof x === 'object') {
                        return x[valueField] == value;
                    } else {
                        return x == value;
                    }
                });
                return typeof foundValue === 'object' && this.dataSource[index] ? this.dataSource[index][this.textField] : foundValue;
            },

            getOptionFromSelectedValue: function(value) {
                let index = this.getIndexFromSelectedValue(value);
                let valueField = this.valueField;
                let foundValue = Object.values(this.dataSource).find(function(x) {
                    if (typeof x === 'object') {
                        return x[valueField] == value;
                    } else {
                        return x == value;
                    }
                });
                return foundValue ?? value;
            },

            popper: function() {
                // update popper position
                if (this.$refs.simpleSelectOptionsList && this.$refs.simpleSelectOptionsList.offsetHeight) {
                    this.popperHeight = (this.$refs.simpleSelectOptionsList.offsetHeight + 0) + 'px';
                }

                let createPopper = window.Popper ? window.Popper.createPopper : null;
                createPopper = !createPopper && window.createPopper ? window.createPopper : null;

                if (typeof createPopper !== 'function') {
                    throw new TypeError('Laravel Simple Select: requires Popper (https://popper.js.org)');
                }

                if (createPopper && this.$refs.simpleSelectButton && this.$refs.simpleSelectOptionsContainer) {
                    this.popperInstance = createPopper(this.$refs.simpleSelectButton, this.$refs.simpleSelectOptionsContainer, {
                        // placement: "auto",
                        placement: "bottom-start",
                        modifiers: [{
                                name: 'offset',
                                options: {
                                    offset: [0, 0],
                                },
                            },
                            {
                                name: "preventOverflow",
                                options: {
                                    boundary: "clippingParents"
                                },
                            },
                            {
                                name: "flip",
                                options: {
                                    padding: 20,
                                    allowedAutoPlacements: ['top', 'bottom'],
                                }
                            }
                        ]
                    });
                }
            },

            scrollToOption: function() {
                try {
                    if (this.selected && this.$refs.simpleSelectOptionsList) {
                        let focusIndex = 0;
                        if (this.multiple) {
                            let lastSelected = this.selected.length > 0 ? this.selected[this.selected.length - 1] : '';
                            focusIndex = lastSelected ? this.getIndexFromSelectedValue(lastSelected) : 0;
                        } else {
                            focusIndex = this.getIndexFromSelectedValue(this.selected);
                        }
                        // let nonListItem = 3;
                        // let totalListItem = this.$refs.simpleSelectOptionsList.children.length > nonListItem ? this.$refs.simpleSelectOptionsList.children.length - nonListItem : 0;
                        let optionsList = this.$refs.simpleSelectOptionsList.querySelectorAll(':scope > li');
                        let totalOptionsList = optionsList.length;
                        if (totalOptionsList > 0) {
                            let offsetTop = optionsList[focusIndex].offsetTop;
                            this.$refs.simpleSelectOptionsList.scrollTop = offsetTop || 0;
                            // optionsList[focusIndex].focus();                            
                        } else {
                            this.$refs.simpleSelectOptionsList.scrollTop = 0;
                        }
                    } else {
                        this.$refs.simpleSelectOptionsList.scrollTop = 0;
                    }
                    this.$refs.simpleSelectOptionsSearch.focus();
                } catch (e) {}

            }
        }
    }
    window.SimpleSelect = SimpleSelect;
</script>
