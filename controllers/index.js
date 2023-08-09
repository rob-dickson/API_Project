
const mongodb = require('../db/connect');
const ObjectId = require('mongodb').ObjectId;

const getAssetData = async (req, res) => {
    try {
        const result = await mongodb.getDb().db('AssetsTest').collection('assets').find();
        result.toArray().then((lists) => {
            res.setHeader('Content-Type', 'application/json');
            res.status(200).json(lists);
        });
    } catch (err) {
        res.status(500).json(err);
    }
};

const getAssetObject = async (req, res) => {
    try {
        if (!ObjectId.isValid(req.params.id)) {
            res.status(400).json('Must use a valid Asset id to find a Asset.');
        }
        const userId = new ObjectId(req.params.id);
        const result = await mongodb.getDb().db('AssetsTest').collection('assets').find({ _id: userId });
        result.toArray().then((lists) => {
            res.setHeader('Content-Type', 'application/json');
            res.status(200).json(lists[0]);
        });
    } catch (err) {
        res.status(500).json(err);
    }
};

const createAsset = async (req, res) => {
    try {
        const assetsArray =
        {
            "Assets": [
                {
                    Name: req.body.Name,
                    Description: req.body.Description,
                    Path: req.body.Path,
                    UserDownloadable: req.body.UserDownloadable,
                    IsLocal: req.body.IsLocal,
                    AssetType: req.body.AssetType,
                    AssetUsage: req.body.AssetUsage,
                    ThumbnailPath: req.body.ThumbnailPath,
                    GenerateThumbnailForExternalURL: req.body.GenerateThumbnailForExternalURL,
                    GroupId: req.body.GroupId,
                    ProductId: req.body.ProductId,
                    AssetCategoryId: req.body.AssetCategoryId
                }
            ]
        };

        const response = await mongodb.getDb().db('AssetsTest').collection('assets').insertMany(assetsArray);
        if (response.acknowledged) {
            res.status(201).json(response);
        } else {
            res.status(500).json(response.error || 'Error occurred while creating Asset.');
        }
    } catch (err) {
        res.status(500).json(err);
    }
};

const updateAsset = async (req, res) => {
    try {
        const userId = new ObjectId(req.params.id);
        const Asset = {
            Name: req.body.Name,
            Description: req.body.Description,
            Path: req.body.Path,
            UserDownloadable: req.body.UserDownloadable,
            IsLocal: req.body.IsLocal,
            AssetType: req.body.AssetType,
            AssetUsage: req.body.AssetUsage,
            ThumbnailPath: req.body.ThumbnailPath,
            GenerateThumbnailForExternalURL: req.body.GenerateThumbnailForExternalURL,
            GroupId: req.body.GroupId,
            ProductId: req.body.ProductId,
            AssetCategoryId: req.body.AssetCategoryId
        };
        const response = await mongodb.getDb().db('AssetsTest').collection('assets').replaceOne({ _id: userId }, Asset);
        if (response.modifiedCount > 0) {
            res.status(204).send();
        } else {
            res.status(500).json(response.error || 'Error occurred while updating Asset.');
        }
        console.log(req.body)
    } catch (err) {
        res.status(500).json(err);
    }
};

const deleteAsset = async (req, res) => {
    try {
        if (!ObjectId.isValid(req.params.id)) {
            res.status(400).json('Must use a valid Asset id to delete a Asset.');
        }
        const userId = new ObjectId(req.params.id);
        const response = await mongodb.getDb().db('AssetsTest').collection('assets').deleteOne({ _id: userId }, true);
        console.log(response);
        if (response.deletedCount > 0) {
            res.status(200).send();
        } else {
            res.status(500).json(response.error || 'Error occurred while deleting Asset');
        }
    } catch (err) {
        res.status(500).json(err);
    }
};


module.exports = { getAssetData, getAssetObject, createAsset, updateAsset, deleteAsset };