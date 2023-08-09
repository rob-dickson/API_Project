const validator = require('../helpers/validation');

const saveAsset = (req, res, next) => {
    const validationRule = {
        Name: 'required|string',
        Description: 'required|string',
        Path: 'required|string',
        UserDownloadable: 'required|boolean',
        IsLocal: 'required|boolean',
        AssetType: 'required|integer',
        AssetUsage: 'required|string',
        ThumbnailPath: 'null',
        GenerateThumbnailForExternalURL: 'required|boolean',
        GroupId: 'null',
        ProductId: 'required|string',
        AssetCategoryId: 'null'
    };
    validator(req.body, validationRule, {}, (err, status) => {
        if (!status) {
            res.status(412).send({
                success: false,
                message: 'Validation failed',
                data: err
            });
        } else {
            next();
        }
    });
};

module.exports = {
    saveAsset
};
