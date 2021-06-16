# lunatalk.media


### emv

.env
```
CLIENC_KEY="${CLIENC_KEY}"
```

#### info
```
${domain}/info
```

#### image upload
```
${domain}/media-upload

- payload

form-data

media_name : ${media_name}
media_category : ${media_category}
media_file : ${media_file}



ex)
{
    "state": true,
    "data": {
        "media_id": "20210616112311",
        "media_name": "media_name",
        "media_category": "media_category",
        "media_full_url": "http://lunatalk.media.test/storage/media_name/media_category/2216c5d608f17bca5d46992d4afc0a4ac86540fc/9eb3018b-6edc-4bf6-bb6f-191b5a8e34cc.png",
        "dest_full_path": "/storage/media_name/media_category/2216c5d608f17bca5d46992d4afc0a4ac86540fc/9eb3018b-6edc-4bf6-bb6f-191b5a8e34cc.png",
        "dest_path": "/storage/media_name/media_category/2216c5d608f17bca5d46992d4afc0a4ac86540fc",
        "new_file_name": "9eb3018b-6edc-4bf6-bb6f-191b5a8e34cc.png",
        "original_name": "img (1).png",
        "file_type": "image/png",
        "file_size": 44437,
        "file_extension": "png"
    }
}
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
