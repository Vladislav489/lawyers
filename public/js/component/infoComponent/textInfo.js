class textInfo extends parentComponent {
    static currentId = 0;
    _id = ++textInfo.currentId;
    get idClass() {return this._id;}
    constructor(selector,option) { super(selector, option);}
}
