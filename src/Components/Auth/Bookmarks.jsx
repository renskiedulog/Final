import { Box, CardMedia, Typography, Stack } from '@mui/material';
import { Link } from 'react-router-dom';
import { useEffect, useState } from 'react';
import { makeRequest } from '../../Utils/request';

const Bookmarks = ({ userBookmarks }) => {
    const [animes, setAnimes] = useState([]);
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        const tempAnimes = [];
        setLoading(true)
        userBookmarks.map(bookmark => {
            makeRequest(`/info/${bookmark.name}`, "GET")
                .then((res) => {
                    tempAnimes.push(res?.data);
                    setAnimes([...tempAnimes]);
                })
                .catch((error) => {
                    console.error(`Error fetching data for ${bookmark.name}:`, error);
                });
        });
        setLoading(false)
    }, [userBookmarks]);

    return !loading && <Box sx={{ paddingBottom: '5px'}}>
        <Typography sx={{ fontSize: {xs: '15px', md: "20px", padding: '10px 20px'}}}>Bookmarks:</Typography>
        <Stack flexDirection="row" flexWrap="wrap" sx={{ padding: '0 20px', gap: 2, textAlign: 'center'}}>
            {animes.map(anime => {
                return <Link className='bookmark-link' key={anime.id} to={`/info/${anime.id}`}>
                    <CardMedia component="img" image={anime.image} sx={{ width: {xs: '120px', md: '150px'}, height: {xs: '180px', md: "200px"}, borderRadius: '5px' }} />
                    <Typography sx={{ width: {xs:"120px", md: "150px"}, height: '20px', overflow: 'hidden', margin: '3px 0', color: "#fff", fontSize: {xs: '12px', md: '15px'}}}>{anime.title}</Typography>
                </Link>
            })}
        </Stack>
    </Box>
};

export default Bookmarks;