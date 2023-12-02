import { Box, Typography, Link, Avatar, Button } from '@mui/material';
import { useState, useEffect } from 'react';

const Profile = () => {

  function stringToColor(string) {
    let hash = 0;
    let i;
    for (i = 0; i < string.length; i += 1) {
      hash = string.charCodeAt(i) + ((hash << 5) - hash);
    }
    let color = '#';
  
    for (i = 0; i < 3; i += 1) {
      const value = (hash >> (i * 8)) & 0xff;
      color += `00${value.toString(16)}`.slice(-2);
    }
    return color;
  }
  
  function stringAvatar(name) {
    return {
      sx: {
        bgcolor: stringToColor(name),
      },
      children: `${name.split(' ')[0][0]}${name.split(' ')[1][0]}`,
    };
  }

  const user = JSON.parse(localStorage.getItem("auth"))?.user;
  return <Box
      sx={{
        background: "#fff1",
        minHeight: "50vh",
        borderRadius: "10px",
        paddingBottom: "5px",
        color: "#fff"
      }}>
        <Typography sx={{ fontSize: {xs: '18px', md: '24px', color: '#fff9', padding: '5px 12px', borderBottom: '1px solid #fff5'}}}>Profile</Typography>

        <Box sx={{ display:'flex', alignItems: 'center', justifyContent: 'space-between', padding: '5px 12px'}}>
          <Box sx={{ display: 'flex', gap: 1, alignItems: 'center'}}>
            <Avatar {...stringAvatar('Renato Dulog')} />
            <Box>
              <Typography sx={{ fontSize: {xs: '15px', md: '20px'}}}>Renato dulog</Typography>
              <Typography sx={{ fontSize: {xs: '12px', md: '18px', opacity: '0.7'}}}>renrendulog@gmail.com</Typography>
            </Box>
          </Box>
          <Box sx={{ display: 'flex', gap: 1}}>
            <Button variant="contained" color='secondary'>Change Password</Button>
            <Button variant="contained" color='secondary'>Edit</Button>
          </Box>
        </Box>

    </Box>;
};

export default Profile;
